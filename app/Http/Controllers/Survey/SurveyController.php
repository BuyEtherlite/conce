<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models\Survey\SurveyProject;
use App\Models\Survey\SurveyType;
use App\Models\Survey\SurveyEquipment;
use App\Models\Survey\SurveyMeasurement;
use App\Models\Survey\SurveyDocument;
use App\Models\Survey\SurveyBoundary;
use App\Models\Survey\SurveyReport;
use App\Models\Survey\SurveyFee;
use App\Models\Survey\SurveyInspection;
use App\Models\Finance\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    public function index()
    {
        $totalProjects = SurveyProject::count();
        $activeProjects = SurveyProject::where('status', 'in_progress')->count();
        $completedProjects = SurveyProject::where('status', 'completed')->count();
        $pendingProjects = SurveyProject::where('status', 'pending')->count();

        $recentProjects = SurveyProject::with(['surveyType', 'client', 'surveyor'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $equipmentCount = SurveyEquipment::where('status', 'available')->count();
        $totalEquipment = SurveyEquipment::count();

        return view('survey.index', compact(
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'pendingProjects',
            'recentProjects',
            'equipmentCount',
            'totalEquipment'
        ));
    }

    public function dashboard()
    {
        return $this->index();
    }

    // Projects
    public function projects()
    {
        $projects = SurveyProject::with(['surveyType', 'client', 'surveyor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('survey.projects.index', compact('projects'));
    }

    public function createProject()
    {
        $surveyTypes = SurveyType::where('status', 'active')->get();
        $clients = Customer::all();
        $surveyors = User::where('role', 'surveyor')->orWhere('role', 'admin')->get();

        return view('survey.projects.create', compact('surveyTypes', 'clients', 'surveyors'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'survey_type_id' => 'required|exists:survey_types,id',
            'client_id' => 'required|exists:customers,id',
            'property_address' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'requested_date' => 'required|date',
            'estimated_cost' => 'required|numeric|min:0',
        ]);

        $projectNumber = 'SRV-' . date('Y') . '-' . str_pad(SurveyProject::count() + 1, 4, '0', STR_PAD_LEFT);

        $project = SurveyProject::create([
            'project_number' => $projectNumber,
            'title' => $request->title,
            'description' => $request->description,
            'survey_type_id' => $request->survey_type_id,
            'client_id' => $request->client_id,
            'surveyor_id' => $request->surveyor_id,
            'council_id' => auth()->user()->council_id,
            'department_id' => auth()->user()->department_id,
            'property_address' => $request->property_address,
            'property_coordinates' => $request->property_coordinates,
            'property_area' => $request->property_area,
            'priority' => $request->priority,
            'requested_date' => $request->requested_date,
            'estimated_cost' => $request->estimated_cost,
            'special_requirements' => $request->special_requirements,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('survey.projects.show', $project)
                        ->with('success', 'Survey project created successfully.');
    }

    public function showProject(SurveyProject $project)
    {
        $project->load([
            'surveyType',
            'client',
            'surveyor',
            'measurements',
            'documents',
            'boundaries',
            'fees',
            'inspections',
            'reports'
        ]);

        return view('survey.projects.show', compact('project'));
    }

    public function editProject(SurveyProject $project)
    {
        $surveyTypes = SurveyType::where('status', 'active')->get();
        $clients = Customer::all();
        $surveyors = User::where('role', 'surveyor')->orWhere('role', 'admin')->get();

        return view('survey.projects.edit', compact('project', 'surveyTypes', 'clients', 'surveyors'));
    }

    public function updateProject(Request $request, SurveyProject $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'survey_type_id' => 'required|exists:survey_types,id',
            'client_id' => 'required|exists:customers,id',
            'property_address' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,approved,in_progress,survey_complete,mapping_complete,completed,cancelled',
        ]);

        $project->update($request->all());

        return redirect()->route('survey.projects.show', $project)
                        ->with('success', 'Survey project updated successfully.');
    }

    // Equipment Management
    public function equipment()
    {
        $equipment = SurveyEquipment::orderBy('created_at', 'desc')->paginate(20);

        return view('survey.equipment.index', compact('equipment'));
    }

    public function createEquipment()
    {
        return view('survey.equipment.create');
    }

    public function storeEquipment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_cost' => 'nullable|numeric|min:0',
        ]);

        $equipmentCode = 'EQ-' . strtoupper(substr($request->type, 0, 3)) . '-' . str_pad(SurveyEquipment::count() + 1, 4, '0', STR_PAD_LEFT);

        SurveyEquipment::create([
            'equipment_code' => $equipmentCode,
            'name' => $request->name,
            'type' => $request->type,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'purchase_date' => $request->purchase_date,
            'purchase_cost' => $request->purchase_cost,
            'specifications' => $request->specifications,
            'council_id' => auth()->user()->council_id,
        ]);

        return redirect()->route('survey.equipment.index')
                        ->with('success', 'Equipment added successfully.');
    }

    // Survey Types Management
    public function types()
    {
        $types = SurveyType::orderBy('created_at', 'desc')->paginate(20);

        return view('survey.types.index', compact('types'));
    }

    public function createType()
    {
        return view('survey.types.create');
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
        ]);

        SurveyType::create($request->all());

        return redirect()->route('survey.types.index')
                        ->with('success', 'Survey type created successfully.');
    }

    // Measurements
    public function measurements(SurveyProject $project)
    {
        $measurements = $project->measurements()->with('equipment', 'measuredBy')->paginate(20);
        $equipment = SurveyEquipment::where('status', 'available')->get();

        return view('survey.measurements.index', compact('project', 'measurements', 'equipment'));
    }

    public function storeMeasurement(Request $request, SurveyProject $project)
    {
        $request->validate([
            'point_name' => 'required|string|max:255',
            'point_type' => 'required|string|max:255',
            'x_coordinate' => 'nullable|numeric',
            'y_coordinate' => 'nullable|numeric',
            'z_coordinate' => 'nullable|numeric',
            'elevation' => 'nullable|numeric',
        ]);

        $project->measurements()->create([
            'point_name' => $request->point_name,
            'x_coordinate' => $request->x_coordinate,
            'y_coordinate' => $request->y_coordinate,
            'z_coordinate' => $request->z_coordinate,
            'elevation' => $request->elevation,
            'point_type' => $request->point_type,
            'description' => $request->description,
            'equipment_id' => $request->equipment_id,
            'measured_at' => now(),
            'accuracy' => $request->accuracy,
            'measured_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Measurement added successfully.');
    }

    // Documents
    public function documents(SurveyProject $project)
    {
        $documents = $project->documents()->with('createdBy')->paginate(20);

        return view('survey.documents.index', compact('project', 'documents'));
    }

    public function storeDocument(Request $request, SurveyProject $project)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('survey-documents', $fileName, 'public');

        $project->documents()->create([
            'document_type' => $request->document_type,
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    // Boundaries
    public function boundaries(SurveyProject $project)
    {
        $boundaries = $project->boundaries()->paginate(20);

        return view('survey.boundaries.index', compact('project', 'boundaries'));
    }

    public function storeBoundary(Request $request, SurveyProject $project)
    {
        $request->validate([
            'boundary_name' => 'required|string|max:255',
            'boundary_type' => 'required|string|max:255',
            'coordinates' => 'required|json',
        ]);

        $project->boundaries()->create([
            'boundary_name' => $request->boundary_name,
            'coordinates' => $request->coordinates,
            'length' => $request->length,
            'boundary_type' => $request->boundary_type,
            'marking_type' => $request->marking_type,
            'description' => $request->description,
            'disputed' => $request->boolean('disputed'),
            'dispute_notes' => $request->dispute_notes,
        ]);

        return redirect()->back()->with('success', 'Boundary added successfully.');
    }

    // Reports
    public function reports()
    {
        $reports = SurveyReport::with(['project', 'preparedBy'])->paginate(20);

        return view('survey.reports.index', compact('reports'));
    }

    public function createReport(SurveyProject $project)
    {
        return view('survey.reports.create', compact('project'));
    }

    public function storeReport(Request $request, SurveyProject $project)
    {
        $request->validate([
            'report_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'executive_summary' => 'nullable|string',
        ]);

        $project->reports()->create([
            'report_type' => $request->report_type,
            'title' => $request->title,
            'executive_summary' => $request->executive_summary,
            'methodology' => $request->methodology,
            'findings' => $request->findings,
            'recommendations' => $request->recommendations,
            'limitations' => $request->limitations,
            'prepared_by' => auth()->id(),
        ]);

        return redirect()->route('survey.reports.index')
                        ->with('success', 'Survey report created successfully.');
    }

    // Fees
    public function fees(SurveyProject $project)
    {
        $fees = $project->fees()->paginate(20);

        return view('survey.fees.index', compact('project', 'fees'));
    }

    public function storeFee(Request $request, SurveyProject $project)
    {
        $request->validate([
            'fee_type' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $project->fees()->create([
            'fee_type' => $request->fee_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'total_amount' => $request->amount * $request->quantity,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Fee added successfully.');
    }

    // Analytics
    public function analytics()
    {
        $data = [
            'projectsByStatus' => SurveyProject::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'projectsByType' => SurveyProject::with('surveyType')
                ->selectRaw('survey_type_id, COUNT(*) as count')
                ->groupBy('survey_type_id')
                ->get()
                ->pluck('count', 'surveyType.name')
                ->toArray(),
            'monthlyProjects' => SurveyProject::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray(),
            'totalRevenue' => SurveyFee::where('status', 'paid')->sum('total_amount'),
            'pendingRevenue' => SurveyFee::where('status', 'pending')->sum('total_amount'),
        ];

        return view('survey.analytics', compact('data'));
    }
}