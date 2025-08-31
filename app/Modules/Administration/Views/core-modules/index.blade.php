@extends('layouts.admin')

@section('title', 'Core Modules Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Core Modules Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('administration.users.index') }}">Administration</a></li>
                        <li class="breadcrumb-item active">Core Modules</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cubes me-2"></i>
                        System Modules
                    </h5>
                    <p class="text-muted small mb-0">Enable or disable system modules. Core modules cannot be disabled.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($modules as $moduleKey => $module)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 module-card {{ $moduleStatuses[$moduleKey] ?? false ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title">
                                                    <i class="{{ $module['icon'] }} me-2"></i>
                                                    {{ $module['name'] }}
                                                    @if($module['core'])
                                                        <span class="badge bg-primary ms-2">Core</span>
                                                    @endif
                                                </h6>
                                                <p class="card-text text-muted small">{{ $module['description'] }}</p>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input module-toggle"
                                                       type="checkbox"
                                                       id="module_{{ $moduleKey }}"
                                                       data-module="{{ $moduleKey }}"
                                                       {{ ($moduleStatuses[$moduleKey] ?? false) ? 'checked' : '' }}
                                                       {{ $module['core'] ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="module_{{ $moduleKey }}">
                                                    {{ ($moduleStatuses[$moduleKey] ?? false) ? 'Enabled' : 'Disabled' }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="module-status">
                                            @if($moduleStatuses[$moduleKey] ?? false)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Inactive
                                                </span>
                                            @endif

                                            @if($module['core'])
                                                <span class="badge bg-primary ms-2">
                                                    <i class="fas fa-lock me-1"></i>Protected
                                                </span>
                                            @endif
                                        </div>

                                        @if($module['features'] && count($module['features']) > 0)
                                            <div class="mt-3">
                                                <h6 class="text-muted mb-2">Features:</h6>
                                                @foreach($module['features'] as $feature)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge {{ $feature['is_active'] ? 'bg-success' : 'bg-secondary' }} me-2">
                                                            {{ $feature['name'] }}
                                                        </span>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input feature-toggle"
                                                                   type="checkbox"
                                                                   {{ $feature['is_active'] ? 'checked' : '' }}
                                                                   data-module="{{ $moduleKey }}"
                                                                   data-feature="{{ $feature['id'] }}"
                                                                   data-name="{{ $feature['name'] }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.module-card {
    transition: all 0.3s ease;
    cursor: default;
}

.module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-switch .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.form-switch .form-check-input:disabled {
    opacity: 0.5;
}

.module-status .badge {
    font-size: 0.75em;
}
</style>

@push('scripts')
<script>
        $(document).ready(function() {
            $('.module-toggle').change(function() {
                const moduleId = $(this).data('module');
                const moduleName = $(this).data('name');
                const isEnabled = $(this).is(':checked');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post(`/council-admin/modules/${moduleId}/toggle`, {
                    status: isEnabled
                }).done(function(response) {
                    if (response.success) {
                        const alertClass = isEnabled ? 'alert-success' : 'alert-warning';
                        showAlert(response.message, alertClass);
                    }
                }).fail(function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert(response.error || 'An error occurred', 'alert-danger');
                    // Revert the toggle
                    $('.module-toggle[data-module="' + moduleId + '"]').prop('checked', !isEnabled);
                });
            });

            $('.feature-toggle').change(function() {
                const moduleId = $(this).data('module');
                const featureId = $(this).data('feature');
                const featureName = $(this).data('name');
                const isEnabled = $(this).is(':checked');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post(`/council-admin/modules/${moduleId}/features/${featureId}/toggle`, {
                    status: isEnabled
                }).done(function(response) {
                    if (response.success) {
                        const alertClass = isEnabled ? 'alert-success' : 'alert-warning';
                        showAlert(response.message, alertClass);

                        // Update badge color
                        const badge = $(`.feature-toggle[data-feature="${featureId}"]`).closest('.d-flex').find('.badge');
                        if (isEnabled) {
                            badge.removeClass('bg-secondary').addClass('bg-success');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-secondary');
                        }
                    }
                }).fail(function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert(response.error || 'An error occurred', 'alert-danger');
                    // Revert the toggle
                    $(`.feature-toggle[data-feature="${featureId}"]`).prop('checked', !isEnabled);
                });
            });

            function showAlert(message, alertClass) {
                const alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('.container-fluid').prepend(alert);

                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 3000);
            }
        });
    </script>
@endpush
@endsection