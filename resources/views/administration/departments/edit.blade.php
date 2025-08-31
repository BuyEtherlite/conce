@extends('layouts.admin')

@section('title', 'Edit Department')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 text-primary fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Department
                    </h1>
                    <p class="text-muted mb-0">Update department information and module access</p>
                </div>
                <div>
                    <a href="{{ route('administration.departments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Departments
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Department Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('administration.departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $department->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $department->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Module Access</label>
                            <div class="row">
                                @foreach($availableModules as $moduleKey => $moduleName)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="modules_access[]" value="{{ $moduleKey }}" 
                                                   id="module_{{ $moduleKey }}"
                                                   {{ in_array($moduleKey, old('modules_access', $department->modules_access ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="module_{{ $moduleKey }}">
                                                {{ $moduleName }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="is_active" value="1" id="is_active"
                                       {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Department
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Department
                            </button>
                            <a href="{{ route('administration.departments.show', $department) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Information</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Edit the department information and select which modules this department should have access to.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li>• Department name should be descriptive</li>
                        <li>• Department code should be unique</li>
                        <li>• Select appropriate modules for department access</li>
                        <li>• Inactive departments cannot access the system</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
