<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Council;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Department::with('council');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by council
        if ($request->filled('council_id')) {
            $query->where('council_id', $request->council_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $departments = $query->orderBy('name')->paginate(15);
        $councils = Council::where('is_active', true)->get();

        return view('admin.departments.index', compact('departments', 'councils'));
    }

    public function create()
    {
        $councils = Council::where('is_active', true)->get();
        return view('admin.departments.create', compact('councils'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments',
            'description' => 'nullable|string|max:500',
            'council_id' => 'required|exists:councils,id',
            'head_of_department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'budget_code' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        Department::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'council_id' => $request->council_id,
            'head_of_department' => $request->head_of_department,
            'phone' => $request->phone,
            'email' => $request->email,
            'budget_code' => $request->budget_code,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully!');
    }

    public function show(Department $department)
    {
        $department->load(['council', 'users']);
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $councils = Council::where('is_active', true)->get();
        return view('admin.departments.edit', compact('department', 'councils'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
            'description' => 'nullable|string|max:500',
            'council_id' => 'required|exists:councils,id',
            'head_of_department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'budget_code' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $department->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'council_id' => $request->council_id,
            'head_of_department' => $request->head_of_department,
            'phone' => $request->phone,
            'email' => $request->email,
            'budget_code' => $request->budget_code,
            'is_active' => $request->boolean('is_active', $department->is_active),
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        // Check if department has users
        if ($department->users()->count() > 0) {
            return redirect()
                ->route('admin.departments.index')
                ->with('error', 'Cannot delete department with existing users!');
        }

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully!');
    }

    public function toggleStatus(Department $department)
    {
        $department->update(['is_active' => !$department->is_active]);

        $status = $department->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('admin.departments.index')
            ->with('success', "Department {$status} successfully!");
    }
}
