<?php

namespace App\Modules\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('offices')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $availableModules = $this->getAvailableModules();
        return view('admin.departments.create', compact('availableModules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'modules_access' => 'nullable|array',
        ]);

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'modules_access' => json_encode($request->modules_access ?? []),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $availableModules = $this->getAvailableModules();
        $selectedModules = json_decode($department->modules_access, true) ?: [];
        return view('admin.departments.edit', compact('department', 'availableModules', 'selectedModules'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'modules_access' => 'nullable|array',
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'modules_access' => json_encode($request->modules_access ?? []),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }

    private function getAvailableModules()
    {
        return [
            'housing' => 'Housing & Community Services',
            'finance' => 'Financial Management',
            'planning' => 'Urban Planning & Development',
            'administration' => 'Administration & CRM',
            'utilities' => 'Utilities & Infrastructure',
            'health' => 'Health & Safety Services',
            'cemeteries' => 'Cemeteries & Grave Register',
            'inventory' => 'Stores & Inventory',
            'committee' => 'Committee & Governance',
            'reports' => 'Reports & Analytics',
            'facilities' => 'Facility Management',
            'property' => 'Property Management',
            'water' => 'Water Management',
            'emergency' => 'Emergency Services',
            'quality' => 'Quality Assurance',
            'security' => 'Access Control & Security',
            'audit' => 'Audit Trail'
        ];
    }
}