<?php

namespace App\Modules\Survey\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Survey\Models\SurveyProject;
use App\Modules\Survey\Models\SurveyEquipment;
use App\Modules\Survey\Models\SurveyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $stats = [
            'active_projects' => SurveyProject::where('status', 'active')->count(),
            'completed_projects' => SurveyProject::where('status', 'completed')->count(),
            'total_revenue' => SurveyProject::where('status', 'completed')->sum('total_cost'),
            'equipment_count' => SurveyEquipment::where('status', 'active')->count(),
        ];

        $recentProjects = SurveyProject::with(['type', 'client'])
            ->latest()
            ->take(10)
            ->get();

        return view('survey.index', compact('stats', 'recentProjects'));
    }

    public function projects()
    {
        $projects = SurveyProject::with(['type', 'client'])
            ->paginate(15);

        return view('survey.projects.index', compact('projects'));
    }

    public function createProject()
    {
        $surveyTypes = SurveyType::all();
        return view('survey.projects.create', compact('surveyTypes'));
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'project_number' => 'required|string|unique:survey_projects,project_number',
            'client_name' => 'required|string|max:255',
            'survey_type_id' => 'required|exists:survey_types,id',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
        ]);

        $validated['status'] = 'pending';
        $validated['progress_percentage'] = 0;
        $validated['created_by'] = auth()->id();

        SurveyProject::create($validated);

        return redirect()->route('survey.projects.index')
            ->with('success', 'Survey project created successfully.');
    }

    public function showProject(SurveyProject $project)
    {
        $project->load(['type', 'measurements', 'documents', 'inspections']);
        return view('survey.projects.show', compact('project'));
    }

    public function editProject(SurveyProject $project)
    {
        $surveyTypes = SurveyType::all();
        return view('survey.projects.edit', compact('project', 'surveyTypes'));
    }

    public function updateProject(Request $request, SurveyProject $project)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'survey_type_id' => 'required|exists:survey_types,id',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'actual_completion_date' => 'nullable|date',
            'status' => 'required|in:pending,active,completed,cancelled',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $project->update($validated);

        return redirect()->route('survey.projects.show', $project)
            ->with('success', 'Survey project updated successfully.');
    }

    public function cadastralSurveys()
    {
        $cadastralProjects = SurveyProject::whereHas('type', function($query) {
            $query->where('name', 'like', '%cadastral%');
        })->paginate(15);

        return view('survey.cadastral.index', compact('cadastralProjects'));
    }

    public function equipment()
    {
        $equipment = SurveyEquipment::with('type')->paginate(15);
        return view('survey.equipment.index', compact('equipment'));
    }

    public function reports()
    {
        $monthlyStats = DB::table('survey_projects')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_projects'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_cost ELSE 0 END) as total_revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('survey.reports.index', compact('monthlyStats'));
    }
}
