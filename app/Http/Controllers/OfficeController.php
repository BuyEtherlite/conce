<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Council;
use App\Models\Department;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Office::with(['council', 'department']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by council
        if ($request->filled('council_id')) {
            $query->where('council_id', $request->council_id);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $offices = $query->orderBy('name')->paginate(15);
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('admin.offices.index', compact('offices', 'councils', 'departments'));
    }

    public function create()
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        return view('admin.offices.create', compact('councils', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:offices',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        Office::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'council_id' => $request->council_id,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('council-admin.offices.index')
            ->with('success', 'Office created successfully!');
    }

    public function show(Office $office)
    {
        $office->load(['council', 'department']);
        return view('admin.offices.show', compact('office'));
    }

    public function edit(Office $office)
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        return view('admin.offices.edit', compact('office', 'councils', 'departments'));
    }

    public function update(Request $request, Office $office)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:offices,code,' . $office->id,
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $office->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'council_id' => $request->council_id,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', $office->is_active),
        ]);

        return redirect()
            ->route('council-admin.offices.index')
            ->with('success', 'Office updated successfully!');
    }

    public function destroy(Office $office)
    {
        $office->delete();

        return redirect()
            ->route('council-admin.offices.index')
            ->with('success', 'Office deleted successfully!');
    }

    public function toggleStatus(Office $office)
    {
        $office->update(['is_active' => !$office->is_active]);

        $status = $office->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('council-admin.offices.index')
            ->with('success', "Office {$status} successfully!");
    }
}
