<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Council;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::with(['council', 'department']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
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

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $councils = Council::all();
        $departments = Department::all();

        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_MANAGER => 'Manager',
            User::ROLE_USER => 'User'
        ];

        return view('admin.users.index', compact('users', 'councils', 'departments', 'roles'));
    }

    public function create()
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_MANAGER => 'Manager',
            User::ROLE_USER => 'User'
        ];

        return view('admin.users.create', compact('councils', 'departments', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'nullable|string|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'role' => ['required', Rule::in([
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
                User::ROLE_MANAGER,
                User::ROLE_USER
            ])],
            'job_title' => 'nullable|string|max:255',
            'employment_status' => 'required|in:active,inactive,terminated',
            'active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'council_id' => $request->council_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
            'job_title' => $request->job_title,
            'employment_status' => $request->employment_status,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['council', 'department']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_MANAGER => 'Manager',
            User::ROLE_USER => 'User'
        ];

        return view('admin.users.edit', compact('user', 'councils', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'employee_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'role' => ['required', Rule::in([
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
                User::ROLE_MANAGER,
                User::ROLE_USER
            ])],
            'job_title' => 'nullable|string|max:255',
            'employment_status' => 'required|in:active,inactive,terminated',
            'active' => 'boolean'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'council_id' => $request->council_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
            'job_title' => $request->job_title,
            'employment_status' => $request->employment_status,
            'active' => $request->boolean('active', $user->active),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deletion of super admin users
        if ($user->role === User::ROLE_SUPER_ADMIN && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Cannot delete the last super admin user!');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleStatus(User $user)
    {
        // Prevent deactivating the last super admin
        if ($user->role === User::ROLE_SUPER_ADMIN && $user->active && User::where('role', User::ROLE_SUPER_ADMIN)->where('active', true)->count() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Cannot deactivate the last active super admin!');
        }

        $user->update(['active' => !$user->active]);

        $status = $user->active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$status} successfully!");
    }
}
