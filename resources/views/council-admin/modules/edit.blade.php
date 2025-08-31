@extends('layouts.council-admin')

@section('title', 'Edit Module')
@section('page-title', 'Edit Module')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Module</h1>
        <a href="{{ route('council-admin.modules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Modules
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Module Information</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('council-admin.modules.update', $module) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Module Key</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $module->name) }}" readonly>
                                    <small class="form-text text-muted">Module key cannot be changed</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" 
                                           value="{{ old('display_name', $module->display_name) }}" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $module->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">Icon Class</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $module->icon) }}" 
                                           placeholder="fas fa-cube">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">FontAwesome icon class (e.g., fas fa-home)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', $module->sort_order) }}" min="1">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="row">
                                @php
                                    $availablePermissions = [
                                        'view' => 'View',
                                        'create' => 'Create',
                                        'edit' => 'Edit',
                                        'delete' => 'Delete',
                                        'manage' => 'Manage'
                                    ];
                                    $modulePermissions = is_array($module->permissions) ? $module->permissions : [];
                                @endphp
                                @foreach($availablePermissions as $permission => $label)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="permissions[]" value="{{ $permission }}" 
                                                   id="permission_{{ $permission }}"
                                                   {{ in_array($permission, old('permissions', $modulePermissions)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Module Active
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Update Module</button>
                            <a href="{{ route('council-admin.modules.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Module Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong>
                        <span class="badge badge-{{ $module->is_active ? 'success' : 'danger' }}">
                            {{ $module->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Module Type:</strong>
                        <span class="badge badge-{{ $module->is_core ? 'info' : 'secondary' }}">
                            {{ $module->is_core ? 'Core Module' : 'Custom Module' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Version:</strong>
                        <span class="text-muted">{{ $module->version ?? '1.0.0' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <span class="text-muted">{{ $module->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <strong>Last Updated:</strong>
                        <span class="text-muted">{{ $module->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            @if($module->is_core)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Core Module</h6>
                </div>
                <div class="card-body">
                    <p class="text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This is a core system module. Some settings may be restricted to maintain system integrity.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
