@extends('layouts.council-admin')

@section('title', 'Module Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Module Management</h1>
                    <p class="text-muted mb-0">Configure and manage system modules</p>
                </div>
                <div>
                    <a href="{{ route('council-admin.modules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Module
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                @foreach($modules as $module)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 {{ $module->is_active ? 'border-success' : 'border-secondary' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-{{ $module->getIcon() }} fa-lg text-primary me-2"></i>
                                <h6 class="mb-0">{{ $module->display_name }}</h6>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input module-toggle" 
                                       type="checkbox" 
                                       id="module-{{ $module->id }}"
                                       data-module-id="{{ $module->id }}"
                                       {{ $module->is_active ? 'checked' : '' }}
                                       {{ $module->is_core ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted">{{ $module->description }}</p>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="border-end">
                                        <div class="h5 mb-0 text-success">{{ $module->getEnabledFeaturesCount() }}</div>
                                        <small class="text-muted">Enabled</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="h5 mb-0 text-muted">{{ $module->getTotalFeaturesCount() }}</div>
                                    <small class="text-muted">Total Features</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-{{ $module->is_active ? 'success' : 'secondary' }}">
                                        {{ $module->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($module->is_core)
                                        <span class="badge bg-primary">Core</span>
                                    @endif
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('council-admin.modules.show', $module->id) }}" 
                                       class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('council-admin.modules.edit', $module->id) }}" 
                                       class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$module->is_core)
                                        <button class="btn btn-outline-danger delete-module" 
                                                data-module-id="{{ $module->id }}"
                                                data-module-name="{{ $module->display_name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($modules->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-cubes fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Modules Available</h4>
                    <p class="text-muted">Get started by adding your first module.</p>
                    <a href="{{ route('council-admin.modules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add First Module
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Module toggle functionality
    $('.module-toggle').change(function() {
        if ($(this).prop('disabled')) return;
        
        const moduleId = $(this).data('module-id');
        const isActive = $(this).is(':checked');
        
        $.ajax({
            url: `/council-admin/modules/${moduleId}/toggle`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update card styling
                    const card = $(this).closest('.card');
                    if (isActive) {
                        card.removeClass('border-secondary').addClass('border-success');
                        card.find('.badge').removeClass('bg-secondary').addClass('bg-success').text('Active');
                    } else {
                        card.removeClass('border-success').addClass('border-secondary');
                        card.find('.badge').removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                    }
                } else {
                    toastr.error(response.message || 'Failed to toggle module');
                    // Revert the toggle
                    $(this).prop('checked', !isActive);
                }
            }.bind(this),
            error: function() {
                toastr.error('Failed to toggle module');
                // Revert the toggle
                $(this).prop('checked', !isActive);
            }.bind(this)
        });
    });

    // Delete module functionality
    $('.delete-module').click(function() {
        const moduleId = $(this).data('module-id');
        const moduleName = $(this).data('module-name');
        
        if (confirm(`Are you sure you want to delete the "${moduleName}" module? This action cannot be undone.`)) {
            $.ajax({
                url: `/council-admin/modules/${moduleId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Remove the card from the view
                        $(this).closest('.col-lg-4').fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(response.message || 'Failed to delete module');
                    }
                }.bind(this),
                error: function() {
                    toastr.error('Failed to delete module');
                }.bind(this)
            });
        }
    });
});
</script>
@endpush
@endsection
