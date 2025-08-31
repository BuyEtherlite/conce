<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use App\Modules\Engineering\Models\EngineeringProject;
use App\Modules\Engineering\Models\AssetRegister;
use App\Modules\Engineering\Models\InfrastructureMaintenanceRequest;
use App\Modules\Engineering\Models\TownPlanningApplication;
use App\Models\Council;
use Illuminate\Http\Request;

class EngineeringController extends Controller
{
    public function index()
    {
        return view('engineering.index');
    }

    // Projects
    public function projects()
    {
        $projects = EngineeringProject::with('council')->paginate(15);
        return view('engineering.projects.index', compact('projects'));
    }

    public function createProject()
    {
        $councils = Council::all();
        return view('engineering.projects.create', compact('councils'));
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'council_id' => 'required|exists:councils,id',
            'project_number' => 'required|string|max:255|unique:engineering_projects',
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'planned_completion_date' => 'required|date|after:start_date',
            'project_manager' => 'required|string|max:255',
            'contractor' => 'nullable|string|max:255',
            'status' => 'required|in:planning,active,on-hold,completed,cancelled',
        ]);

        EngineeringProject::create($validated);

        return redirect()->route('engineering.projects.index')
            ->with('success', 'Engineering project created successfully.');
    }

    // Infrastructure
    public function infrastructure()
    {
        return view('engineering.infrastructure.index');
    }

    // Assets
    public function assets()
    {
        $assets = AssetRegister::paginate(15);
        return view('engineering.assets.index', compact('assets'));
    }

    public function createAsset()
    {
        return view('engineering.assets.create');
    }

    // Maintenance
    public function maintenance()
    {
        $requests = InfrastructureMaintenanceRequest::paginate(15);
        return view('engineering.maintenance.index', compact('requests'));
    }

    // Work Orders
    public function workOrders()
    {
        return view('engineering.work-orders.index');
    }

    public function createWorkOrder()
    {
        return view('engineering.work-orders.create');
    }

    // Surveys
    public function surveys()
    {
        return view('engineering.surveys.index');
    }

    public function createSurvey()
    {
        return view('engineering.surveys.create');
    }

    public function storeSurvey(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Survey created successfully');
    }

    // Inspections
    public function inspections()
    {
        return view('engineering.inspections.index');
    }

    // Town Planning
    public function townPlanning()
    {
        return view('engineering.planning.index');
    }

    public function planningApplications()
    {
        $applications = TownPlanningApplication::paginate(15);
        return view('engineering.planning.applications.index', compact('applications'));
    }

    public function createPlanningApplication()
    {
        return view('engineering.planning.applications.create');
    }

    public function storePlanningApplication(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'property_address' => 'required|string',
            'application_type' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_use' => 'required|string|max:255',
        ]);

        TownPlanningApplication::create($validated);

        return redirect()->route('engineering.planning.applications.index')
            ->with('success', 'Planning application submitted successfully.');
    }

    public function zoning()
    {
        return view('engineering.planning.zoning.index');
    }

    public function planningApprovals()
    {
        return view('engineering.planning.approvals.index');
    }

    // Architectural Services
    public function architecturalServices()
    {
        return view('engineering.architectural.index');
    }

    public function architecturalProjects()
    {
        return view('engineering.architectural.projects.index');
    }

    public function createArchitecturalProject()
    {
        return view('engineering.architectural.projects.create');
    }
}
