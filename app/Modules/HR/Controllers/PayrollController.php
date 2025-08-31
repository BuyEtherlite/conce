<?php

namespace App\Modules\HR\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HR\Models\HrPayroll;
use App\Modules\HR\Models\HrEmployee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = HrPayroll::with(['employee', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('hr.payroll.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = HrEmployee::where('status', 'active')->get();
        return view('hr.payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'pay_period' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'tax_deductions' => 'nullable|numeric|min:0',
            'insurance_deductions' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Calculate payroll
        $grossPay = $data['basic_salary'] + ($data['overtime_pay'] ?? 0) + ($data['allowances'] ?? 0);
        $totalDeductions = ($data['tax_deductions'] ?? 0) + ($data['insurance_deductions'] ?? 0) + ($data['other_deductions'] ?? 0);
        $netPay = $grossPay - $totalDeductions;
        
        $data['gross_pay'] = $grossPay;
        $data['total_deductions'] = $totalDeductions;
        $data['net_pay'] = $netPay;
        $data['processed_by'] = auth()->id();
        $data['payment_status'] = 'pending';

        HrPayroll::create($data);

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll record created successfully');
    }

    public function show(HrPayroll $payroll)
    {
        $payroll->load(['employee', 'processedBy']);
        return view('hr.payroll.show', compact('payroll'));
    }

    public function edit(HrPayroll $payroll)
    {
        $employees = HrEmployee::where('status', 'active')->get();
        return view('hr.payroll.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, HrPayroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'pay_period' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'tax_deductions' => 'nullable|numeric|min:0',
            'insurance_deductions' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Recalculate payroll
        $grossPay = $data['basic_salary'] + ($data['overtime_pay'] ?? 0) + ($data['allowances'] ?? 0);
        $totalDeductions = ($data['tax_deductions'] ?? 0) + ($data['insurance_deductions'] ?? 0) + ($data['other_deductions'] ?? 0);
        $netPay = $grossPay - $totalDeductions;
        
        $data['gross_pay'] = $grossPay;
        $data['total_deductions'] = $totalDeductions;
        $data['net_pay'] = $netPay;

        if ($data['payment_status'] === 'paid' && $payroll->payment_status !== 'paid') {
            $data['payment_date'] = now();
        }

        $payroll->update($data);

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll record updated successfully');
    }

    public function destroy(HrPayroll $payroll)
    {
        if ($payroll->payment_status === 'paid') {
            return redirect()->route('hr.payroll.index')->with('error', 'Cannot delete paid payroll records');
        }

        $payroll->delete();

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll record deleted successfully');
    }
}
