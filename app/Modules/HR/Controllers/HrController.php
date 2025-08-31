<?php

namespace App\Modules\HR\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HR\Models\Employee;
use App\Modules\HR\Models\Attendance;
use App\Modules\HR\Models\PayrollRun;
use App\Modules\HR\Models\Payslip;
use App\Modules\HR\Models\FaceEnrollment;
use App\Modules\HR\Models\SalaryAdjustment;
use App\Modules\HR\Models\CurrencyRate;
use App\Models\HR\HrPayroll;
use App\Models\HR\HrEmployee;
use App\Models\HR\HrDepartment;
use App\Models\HR\HrLeaveRequest;
use App\Models\Department;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HrController extends Controller
{
    protected $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    public function index()
    {
        $summary = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'present_today' => Attendance::whereDate('attendance_date', today())
                                        ->where('status', 'present')
                                        ->count(),
            'face_enrolled' => Employee::where('face_enrolled', true)->count(),
            'pending_payroll' => PayrollRun::where('status', 'draft')->count()
        ];

        return view('hr.index', compact('summary'));
    }

    // Employee Management
    public function employees()
    {
        $employees = Employee::with(['department', 'attendance' => function($query) {
            $query->whereDate('attendance_date', today());
        }])->paginate(20);

        return view('hr.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        $departments = Department::all();
        $currencies = ['USD', 'ZWG', 'EUR', 'GBP', 'ZAR'];

        return view('hr.employees.create', compact('departments', 'currencies'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'job_title' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'basic_salary_currency' => 'required|string|size:3',
            'allowances' => 'nullable|numeric|min:0',
            'allowances_currency' => 'nullable|string|size:3',
            'salary_breakdown' => 'nullable|array'
        ]);

        $validated['employee_number'] = $this->generateEmployeeNumber();
        $validated['allowances'] = $validated['allowances'] ?? 0;
        $validated['allowances_currency'] = $validated['allowances_currency'] ?? $validated['basic_salary_currency'];

        $employee = Employee::create($validated);

        // Create salary adjustment record
        SalaryAdjustment::create([
            'employee_id' => $employee->id,
            'old_basic_salary' => 0,
            'old_basic_currency' => $validated['basic_salary_currency'],
            'new_basic_salary' => $validated['basic_salary'],
            'new_basic_currency' => $validated['basic_salary_currency'],
            'old_allowances' => 0,
            'old_allowances_currency' => $validated['allowances_currency'],
            'new_allowances' => $validated['allowances'] ?? 0,
            'new_allowances_currency' => $validated['allowances_currency'],
            'reason' => 'Initial salary setup',
            'effective_date' => $validated['hire_date'],
            'adjusted_by' => auth()->id()
        ]);

        return redirect()->route('hr.employees.show', $employee)
                        ->with('success', 'Employee created successfully!');
    }

    public function editEmployeeSalary(Employee $employee)
    {
        $currencies = ['USD', 'ZWG', 'EUR', 'GBP', 'ZAR'];
        $salaryHistory = $employee->salaryAdjustments()->latest()->take(10)->get();

        return view('hr.employees.edit-salary', compact('employee', 'currencies', 'salaryHistory'));
    }

    public function updateEmployeeSalary(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'basic_salary_currency' => 'required|string|size:3',
            'allowances' => 'nullable|numeric|min:0',
            'allowances_currency' => 'nullable|string|size:3',
            'salary_breakdown' => 'nullable|array',
            'reason' => 'required|string|max:500',
            'effective_date' => 'required|date'
        ]);

        DB::transaction(function () use ($employee, $validated) {
            // Record the change
            SalaryAdjustment::create([
                'employee_id' => $employee->id,
                'old_basic_salary' => $employee->basic_salary,
                'old_basic_currency' => $employee->basic_salary_currency,
                'new_basic_salary' => $validated['basic_salary'],
                'new_basic_currency' => $validated['basic_salary_currency'],
                'old_allowances' => $employee->allowances,
                'old_allowances_currency' => $employee->allowances_currency,
                'new_allowances' => $validated['allowances'] ?? 0,
                'new_allowances_currency' => $validated['allowances_currency'] ?? $validated['basic_salary_currency'],
                'old_breakdown' => $employee->salary_breakdown,
                'new_breakdown' => $validated['salary_breakdown'],
                'reason' => $validated['reason'],
                'effective_date' => $validated['effective_date'],
                'adjusted_by' => auth()->id()
            ]);

            // Update employee salary
            $employee->update([
                'basic_salary' => $validated['basic_salary'],
                'basic_salary_currency' => $validated['basic_salary_currency'],
                'allowances' => $validated['allowances'] ?? 0,
                'allowances_currency' => $validated['allowances_currency'] ?? $validated['basic_salary_currency'],
                'salary_breakdown' => $validated['salary_breakdown']
            ]);

            // Update financial records if needed
            $this->updateFinancialRecords($employee, $validated);
        });

        return redirect()->route('hr.employees.show', $employee)
                        ->with('success', 'Salary updated successfully!');
    }

    // Face Recognition Management
    public function faceEnrollment()
    {
        $employees = Employee::where('status', 'active')->get();
        $enrollments = FaceEnrollment::with(['employee', 'enrolledBy'])
                                   ->latest()
                                   ->take(50)
                                   ->get();

        return view('hr.face-enrollment.index', compact('employees', 'enrollments'));
    }

    public function enrollFace(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'face_image' => 'required|image|max:5120' // 5MB max
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        try {
            // Process the face image
            $result = $this->faceRecognitionService->enrollFace(
                $request->file('face_image'),
                $employee
            );

            if ($result['success']) {
                $employee->update([
                    'face_enrolled' => true,
                    'face_enrolled_at' => now(),
                    'face_encoding_file' => $result['encoding_path']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Face enrolled successfully!',
                    'quality_score' => $result['quality_score']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face enrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Attendance Management
    public function attendance()
    {
        $attendanceToday = Attendance::with('employee')
                                   ->whereDate('attendance_date', today())
                                   ->latest()
                                   ->get();

        return view('hr.attendance.index', compact('attendanceToday'));
    }

    public function faceAttendance()
    {
        $enrolledEmployees = Employee::where('face_enrolled', true)
                                   ->where('status', 'active')
                                   ->count();

        return view('hr.attendance.face-scan', compact('enrolledEmployees'));
    }

    public function processFaceAttendance(Request $request)
    {
        $validated = $request->validate([
            'face_image' => 'required|image|max:2048',
            'action' => 'required|in:check_in,check_out'
        ]);

        try {
            $result = $this->faceRecognitionService->recognizeFace($request->file('face_image'));

            if ($result['success'] && $result['employee']) {
                $employee = $result['employee'];
                $confidence = $result['confidence'];

                // Record attendance
                $attendance = Attendance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => today()
                    ],
                    [
                        'status' => 'present',
                        'created_by' => auth()->id()
                    ]
                );

                if ($validated['action'] === 'check_in') {
                    $attendance->update([
                        'check_in' => now(),
                        'check_in_method' => 'face_scan',
                        'face_confidence_in' => $confidence
                    ]);
                } else {
                    $attendance->update([
                        'check_out' => now(),
                        'check_out_method' => 'face_scan',
                        'face_confidence_out' => $confidence
                    ]);
                    $attendance->calculateHours();
                    $attendance->save();
                }

                return response()->json([
                    'success' => true,
                    'employee' => $employee->full_name,
                    'action' => $validated['action'],
                    'time' => now()->format('H:i:s'),
                    'confidence' => $confidence
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Face not recognized or confidence too low'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face recognition failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateEmployeeNumber(): string
    {
        $year = date('Y');
        $lastEmployee = Employee::whereRaw('employee_number LIKE ?', ["{$year}%"])
                               ->orderBy('employee_number', 'desc')
                               ->first();

        if ($lastEmployee) {
            $lastNumber = intval(substr($lastEmployee->employee_number, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function updateFinancialRecords(Employee $employee, array $salaryData): void
    {
        // This method would integrate with the financial system
        // to update expense accounts, budgets, etc.
        // Implementation depends on your financial module structure
    }

    public function training()
    {
        return view('hr.training.index');
    }

    // Additional HR Methods
    public function programs() { return view('hr.training.programs'); }
    public function courses() { return view('hr.training.courses'); }
    public function certifications() { return view('hr.training.certifications'); }
    public function recruitment() { return view('hr.recruitment.index'); }
    public function jobs() { return view('hr.recruitment.jobs'); }
    public function applications() { return view('hr.recruitment.applications'); }
    public function interviews() { return view('hr.recruitment.interviews'); }
    public function policies() { return view('hr.policies.index'); }
    public function handbook() { return view('hr.policies.handbook'); }
    public function procedures() { return view('hr.policies.procedures'); }
    public function attendanceReports() { return view('hr.attendance.reports'); }
    public function overtime() { return view('hr.attendance.overtime'); }
    public function calculatePayroll() { return view('hr.payroll.calculate'); }
    public function payrollReports() { return view('hr.payroll.reports'); }
    public function leaveCalendar() { return view('hr.leaves.calendar'); }
    public function reviews() { return view('hr.performance.reviews'); }
    public function goals() { return view('hr.performance.goals'); }
    public function appraisals() { return view('hr.performance.appraisals'); }

    public function showEmployee($id) { return view('hr.employees.show', compact('id')); }
    public function editEmployee($id) { return view('hr.employees.edit', compact('id')); }
    public function updateEmployee($id) { return redirect()->route('hr.employees.index'); }
    public function destroyEmployee($id) { return redirect()->route('hr.employees.index'); }
    public function editSalary($id) { return view('hr.employees.edit-salary', compact('id')); }
    public function updateSalary($id) { return redirect()->route('hr.employees.index'); }

    // Leave Management Methods
    public function leaves() { return view('hr.leaves.index'); }
    public function createLeave() { return view('hr.leaves.create'); }
    public function storeLeave() { return redirect()->route('hr.leave.index'); }
    public function showLeave($id) { return view('hr.leaves.show', compact('id')); }
    public function editLeave($id) { return view('hr.leaves.edit', compact('id')); }
    public function updateLeave($id) { return redirect()->route('hr.leave.index'); }
    public function destroyLeave($id) { return redirect()->route('hr.leave.index'); }
    public function approveLeave($id) { return redirect()->route('hr.leave.index'); }
    public function rejectLeave($id) { return redirect()->route('hr.leave.index'); }

    // Performance Management Methods
    public function performance() { return view('hr.performance.index'); }
    public function createReview() { return view('hr.performance.reviews.create'); }
    public function storeReview() { return redirect()->route('hr.performance.reviews'); }
    public function createGoal() { return view('hr.performance.goals.create'); }
    public function storeGoal() { return redirect()->route('hr.performance.goals'); }
    public function createAppraisal() { return view('hr.performance.appraisals.create'); }
    public function storeAppraisal() { return redirect()->route('hr.performance.appraisals'); }

    // Training Management Methods
    public function createProgram() { return view('hr.training.programs.create'); }
    public function storeProgram() { return redirect()->route('hr.training.programs'); }
    public function createCourse() { return view('hr.training.courses.create'); }
    public function storeCourse() { return redirect()->route('hr.training.courses'); }

    // Recruitment Management Methods
    public function createJob() { return view('hr.recruitment.jobs.create'); }
    public function storeJob() { return redirect()->route('hr.recruitment.jobs'); }
    public function showApplication($id) { return view('hr.recruitment.applications.show', compact('id')); }
    public function createInterview() { return view('hr.recruitment.interviews.create'); }
    public function storeInterview() { return redirect()->route('hr.recruitment.interviews'); }

    // Department Management Methods
    public function departments() { return view('hr.departments.index'); }
    public function createDepartment() { return view('hr.departments.create'); }
    public function storeDepartment() { return redirect()->route('hr.departments.index'); }
    public function showDepartment($id) { return view('hr.departments.show', compact('id')); }
    public function editDepartment($id) { return view('hr.departments.edit', compact('id')); }
    public function updateDepartment($id) { return redirect()->route('hr.departments.index'); }
    public function destroyDepartment($id) { return redirect()->route('hr.departments.index'); }

    // Reports Methods
    public function hrReports() { return view('hr.reports.index'); }
    public function payroll() { return view('hr.payroll.index'); }
    public function processPayroll() { return redirect()->route('hr.payroll.index'); }
    public function currencyRates() { return view('hr.currency.rates'); }
    public function updateCurrencyRates() { return redirect()->route('hr.currency.rates'); }
}
<?php

namespace App\Modules\HR\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HrController extends Controller
{
    public function index()
    {
        return view('hr.index');
    }

    public function employees()
    {
        return view('hr.employees.index');
    }

    public function attendance()
    {
        return view('hr.attendance.index');
    }
}
