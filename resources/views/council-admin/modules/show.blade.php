@extends('layouts.council-admin')

@section('title', 'Module Details - ' . $module->display_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-{{ $module->getIcon() }} text-primary me-2"></i>
                        {{ $module->display_name }}
                    </h1>
                    <p class="text-muted mb-0">{{ $module->description }}</p>
                </div>
                <div>
                    <a href="{{ route('council-admin.modules.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Modules
                    </a>
                    <a href="{{ route('council-admin.modules.edit', $module->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Module
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Module Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Module Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <span class="badge bg-{{ $module->is_active ? 'success' : 'danger' }}">
                                        {{ $module->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <div>
                                    <span class="badge bg-{{ $module->is_core ? 'primary' : 'secondary' }}">
                                        {{ $module->is_core ? 'Core Module' : 'Add-on Module' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Version</label>
                                <div>{{ $module->version }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <div>{{ $module->sort_order }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Features</label>
                                <div>
                                    <span class="text-success">{{ $module->getEnabledFeaturesCount() }}</span> enabled / 
                                    <span class="text-muted">{{ $module->getTotalFeaturesCount() }}</span> total
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Features -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Module Features</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="addFeature()">
                                <i class="fas fa-plus me-1"></i>Add Feature
                            </button>
                        </div>
                        <div class="card-body">
                            @if($module->features->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Feature Name</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($module->features->sortBy('sort_order') as $feature)
                                            <tr>
                                                <td>
                                                    <strong>{{ $feature->feature_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $feature->feature_key }}</small>
                                                </td>
                                                <td>{{ $feature->description ?: 'No description available' }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input feature-toggle" 
                                                               type="checkbox" 
                                                               id="feature-{{ $feature->id }}"
                                                               data-module-id="{{ $module->id }}"
                                                               data-feature-id="{{ $feature->id }}"
                                                               {{ $feature->is_enabled ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="feature-{{ $feature->id }}">
                                                            {{ $feature->is_enabled ? 'Enabled' : 'Disabled' }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="editFeature({{ $feature->id }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="deleteFeature({{ $feature->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-cube fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">No Features Available</h5>
                                    <p class="text-muted">This module doesn't have any configured features yet.</p>
                                    <button class="btn btn-primary" onclick="addFeature()">
                                        <i class="fas fa-plus me-1"></i>Add First Feature
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Feature toggle functionality
    $('.feature-toggle').change(function() {
        const moduleId = $(this).data('module-id');
        const featureId = $(this).data('feature-id');
        const isEnabled = $(this).is(':checked');

        $.ajax({
            url: `/council-admin/modules/${moduleId}/features/${featureId}/toggle`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update the label
                    const label = $(`label[for="feature-${featureId}"]`);
                    label.text(isEnabled ? 'Enabled' : 'Disabled');
                } else {
                    toastr.error(response.message || 'Failed to toggle feature');
                    // Revert the toggle
                    $(this).prop('checked', !isEnabled);
                }
            }.bind(this),
            error: function() {
                toastr.error('Failed to toggle feature');
                // Revert the toggle
                $(this).prop('checked', !isEnabled);
            }.bind(this)
        });
    });
});

function addFeature() {
    // TODO: Implement add feature modal
    toastr.info('Add feature functionality coming soon');
}

function editFeature(featureId) {
    // TODO: Implement edit feature modal
    toastr.info('Edit feature functionality coming soon');
}

function deleteFeature(featureId) {
    // TODO: Implement delete feature functionality
    toastr.info('Delete feature functionality coming soon');
}
</script>
@endpush
@endsection
@extends('layouts.council-admin')

@section('title', 'Module Details - ' . $module->display_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-{{ $module->getIcon() }} me-2"></i>
                        {{ $module->display_name }}
                    </h3>
                    <div>
                        <a href="{{ route('council-admin.modules.edit', $module->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('council-admin.modules.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Module Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $module->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Display Name:</strong></td>
                                    <td>{{ $module->display_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $module->description ?: 'No description' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Version:</strong></td>
                                    <td>{{ $module->version }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $module->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $module->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Core Module:</strong></td>
                                    <td>
                                        <span class="badge {{ $module->is_core ? 'bg-warning' : 'bg-info' }}">
                                            {{ $module->is_core ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Module Features</h5>
                            @if($module->features->count() > 0)
                                <div class="list-group">
                                    @foreach($module->features as $feature)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $feature->feature_name }}</h6>
                                                <p class="mb-1 text-muted small">{{ $feature->description }}</p>
                                            </div>
                                            <div>
                                                <span class="badge {{ $feature->is_enabled ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $feature->is_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn-outline-primary ms-2" 
                                                        onclick="toggleFeature({{ $module->id }}, {{ $feature->id }})">
                                                    <i class="fas fa-toggle-{{ $feature->is_enabled ? 'on' : 'off' }}"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No features configured for this module.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFeature(moduleId, featureId) {
    fetch(`/council-admin/modules/${moduleId}/features/${featureId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling the feature.');
    });
}
</script>
@endsection
