@extends('layouts.admin')

@section('title', 'Department Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 text-primary fw-bold">
                        <i class="fas fa-building me-2"></i>Department Management
                    </h1>
                    <p class="text-muted mb-0">Manage organizational departments and their module access</p>
                </div>
                <div>
                    <a href="{{ route('administration.departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Department
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Departments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Departments</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Department</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Offices</th>
                            <th>Module Access</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $department->name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $department->code }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;">
                                        {{ $department->description ?? 'No description' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $department->offices_count ?? $department->offices->count() }}</span>
                                </td>
                                <td>
                                    @if($department->modules_access && is_array($department->modules_access))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach(array_slice($department->modules_access, 0, 3) as $module)
                                                <span class="badge bg-primary">{{ ucfirst($module) }}</span>
                                            @endforeach
                                            @if(count($department->modules_access) > 3)
                                                <span class="badge bg-light text-dark">+{{ count($department->modules_access) - 3 }} more</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No modules assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('administration.departments.show', $department) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('administration.departments.edit', $department) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('administration.departments.destroy', $department) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this department?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-building fa-2x mb-2"></i>
                                        <p>No departments found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($departments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
