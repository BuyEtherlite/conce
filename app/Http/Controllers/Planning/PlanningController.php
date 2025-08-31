<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Planning\PlanningApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlanningController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications' => PlanningApplication::count(),
            'pending' => PlanningApplication::whereIn('status', ['submitted', 'under_review'])->count(),
            'approved' => PlanningApplication::where('status', 'approved')->count(),
            'rejected' => PlanningApplication::where('status', 'rejected')->count(),
        ];
        
        $recent_applications = PlanningApplication::with(['council', 'assignedOfficer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('planning.index', compact('stats', 'recent_applications'));
    }

    public function applications(Request $request)
    {
        $query = PlanningApplication::with(['council', 'assignedOfficer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('application_type')) {
            $query->where('application_type', $request->application_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_number', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_name', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_email', 'LIKE', "%{$search}%")
                  ->orWhere('property_address', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('planning.applications', compact('applications'));
    }

    public function createApplication()
    {
        return view('planning.create-application');
    }

    public function storeApplication(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:20',
            'applicant_address' => 'required|string',
            'property_address' => 'required|string',
            'property_erf_number' => 'nullable|string|max:100',
            'application_type' => 'required|in:residential,commercial,industrial,mixed_use,other',
            'development_description' => 'required|string',
            'proposed_use' => 'required|string',
            'property_size' => 'nullable|numeric|min:0',
            'building_coverage' => 'nullable|numeric|min:0',
            'floor_area' => 'nullable|numeric|min:0',
            'number_of_units' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'documents' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'site_plan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'architectural_plans' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comments' => 'nullable|string',
        ]);

        // Generate application number
        $validated['application_number'] = PlanningApplication::generateApplicationNumber();
        $validated['date_submitted'] = now();

        // Handle file uploads
        if ($request->hasFile('documents')) {
            $validated['documents_path'] = $request->file('documents')->store('planning/documents', 'public');
        }

        if ($request->hasFile('site_plan')) {
            $validated['site_plan_path'] = $request->file('site_plan')->store('planning/site-plans', 'public');
        }

        if ($request->hasFile('architectural_plans')) {
            $validated['architectural_plans_path'] = $request->file('architectural_plans')->store('planning/architectural-plans', 'public');
        }

        $application = PlanningApplication::create($validated);

        return redirect()->route('planning.applications.show', $application)
            ->with('success', 'Planning application submitted successfully!');
    }

    public function showApplication(PlanningApplication $application)
    {
        return view('planning.show-application', compact('application'));
    }

    public function editApplication(PlanningApplication $application)
    {
        return view('planning.edit-application', compact('application'));
    }

    public function updateApplication(Request $request, PlanningApplication $application)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:20',
            'applicant_address' => 'required|string',
            'property_address' => 'required|string',
            'property_erf_number' => 'nullable|string|max:100',
            'application_type' => 'required|in:residential,commercial,industrial,mixed_use,other',
            'development_description' => 'required|string',
            'proposed_use' => 'required|string',
            'property_size' => 'nullable|numeric|min:0',
            'building_coverage' => 'nullable|numeric|min:0',
            'floor_area' => 'nullable|numeric|min:0',
            'number_of_units' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'status' => 'required|in:submitted,under_review,approved,rejected,conditional_approval',
            'conditions' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'documents' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'site_plan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'architectural_plans' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comments' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            if ($application->documents_path && Storage::disk('public')->exists($application->documents_path)) {
                Storage::disk('public')->delete($application->documents_path);
            }
            $validated['documents_path'] = $request->file('documents')->store('planning/documents', 'public');
        }

        if ($request->hasFile('site_plan')) {
            if ($application->site_plan_path && Storage::disk('public')->exists($application->site_plan_path)) {
                Storage::disk('public')->delete($application->site_plan_path);
            }
            $validated['site_plan_path'] = $request->file('site_plan')->store('planning/site-plans', 'public');
        }

        if ($request->hasFile('architectural_plans')) {
            if ($application->architectural_plans_path && Storage::disk('public')->exists($application->architectural_plans_path)) {
                Storage::disk('public')->delete($application->architectural_plans_path);
            }
            $validated['architectural_plans_path'] = $request->file('architectural_plans')->store('planning/architectural-plans', 'public');
        }

        if (in_array($validated['status'], ['approved', 'rejected', 'conditional_approval'])) {
            $validated['date_reviewed'] = now();
            $validated['reviewed_by'] = auth()->user()->name;
        }

        $application->update($validated);

        return redirect()->route('planning.applications.show', $application)
            ->with('success', 'Planning application updated successfully!');
    }

    public function destroyApplication(PlanningApplication $application)
    {
        // Only allow deletion of submitted or under_review applications
        if (!in_array($application->status, ['submitted', 'under_review'])) {
            return redirect()->back()->with('error', 'Cannot delete processed applications.');
        }

        // Delete associated files
        if ($application->documents_path && Storage::disk('public')->exists($application->documents_path)) {
            Storage::disk('public')->delete($application->documents_path);
        }
        if ($application->site_plan_path && Storage::disk('public')->exists($application->site_plan_path)) {
            Storage::disk('public')->delete($application->site_plan_path);
        }
        if ($application->architectural_plans_path && Storage::disk('public')->exists($application->architectural_plans_path)) {
            Storage::disk('public')->delete($application->architectural_plans_path);
        }

        $application->delete();

        return redirect()->route('planning.applications.index')
            ->with('success', 'Planning application deleted successfully!');
    }

    public function approvals()
    {
        return view('planning.approvals');
    }

    public function zoning()
    {
        return view('planning.zoning');
    }

    // Approval methods
    public function createApproval()
    {
        return view('planning.approvals.create');
    }

    public function storeApproval(Request $request)
    {
        // Store approval logic here
        return redirect()->route('planning.approvals.index')->with('success', 'Approval created successfully');
    }

    public function showApproval($id)
    {
        return view('planning.approvals.show', compact('id'));
    }

    public function editApproval($id)
    {
        return view('planning.approvals.edit', compact('id'));
    }

    public function updateApproval(Request $request, $id)
    {
        // Update approval logic here
        return redirect()->route('planning.approvals.index')->with('success', 'Approval updated successfully');
    }

    public function destroyApproval($id)
    {
        // Delete approval logic here
        return redirect()->route('planning.approvals.index')->with('success', 'Approval deleted successfully');
    }

    // Zoning methods
    public function createZoning()
    {
        return view('planning.zoning.create');
    }

    public function storeZoning(Request $request)
    {
        // Store zoning logic here
        return redirect()->route('planning.zoning.index')->with('success', 'Zoning record created successfully');
    }

    public function showZoning($id)
    {
        return view('planning.zoning.show', compact('id'));
    }

    public function editZoning($id)
    {
        return view('planning.zoning.edit', compact('id'));
    }

    public function updateZoning(Request $request, $id)
    {
        // Update zoning logic here
        return redirect()->route('planning.zoning.index')->with('success', 'Zoning record updated successfully');
    }

    public function destroyZoning($id)
    {
        // Delete zoning logic here
        return redirect()->route('planning.zoning.index')->with('success', 'Zoning record deleted successfully');
    }
}
