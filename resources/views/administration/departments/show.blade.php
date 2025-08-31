@extends('layouts.admin')

@section('title', 'Department Details')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 text-primary fw-bold">
                        <i class="fas fa-building me-2"></i>{{ $department->name }}
                    </h1>
                    <p class="text-muted mb-0">Department Details</p>
                </div>
                <div>
                    <a href="{{ route('administration.departments.edit', $department) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit Department
                    </a>
                    <a href="{{ route('administration.departments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Departments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Department Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Department Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Department Name</label>
                                <p class="form-control-plaintext">{{ $department->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Department Code</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ $department->code }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="form-control-plaintext">{{ $department->description ?? 'No description provided' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p class="form-control-plaintext">
                            <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $department->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Module Access -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Module Access</h5>
                </div>
                <div class="card-body">
                    @if($department->modules_access && is_array($department->modules_access) && count($department->modules_access) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($department->modules_access as $module)
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $module)) }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No modules assigned to this department
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Total Offices</span>
                                <span class="badge bg-info">{{ $department->offices->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Total Modules</span>
                                <span class="badge bg-primary">{{ $department->modules_access ? count($department->modules_access) : 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Created</span>
                                <span class="text-dark">{{ $department->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offices -->
            @if($department->offices->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Associated Offices</h5>
                    </div>
                    <div class="card-body">
                        @foreach($department->offices as $office)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $office->name }}</span>
                                <span class="badge {{ $office->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $office->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
