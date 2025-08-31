@extends('layouts.admin')

@section('title', 'Core Modules Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs text-primary"></i> Core Modules Management
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        System Configuration
                    </div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        Enable or disable core system modules and their features to customize your municipal ERP experience.
                    </div>
                    <small class="text-muted">
                        Note: Administration and Finance modules cannot be disabled as they are essential system components.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    <div class="row">
        @foreach($modules as $module)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 module-card {{ $module->is_active ? 'border-success' : 'border-secondary' }}" data-module="{{ $module->id }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <i class="fas fa-{{ $module->icon }} fa-2x {{ $module->is_active ? 'text-success' : 'text-muted' }} mb-2"></i>
                            <h6 class="card-title mb-2">{{ $module->display_name }}</h6>
                            <p class="card-text text-muted small">{{ $module->description }}</p>
                        </div>
                        <div class="ml-3">
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input module-toggle"
                                       id="module_{{ $module->id }}"
                                       data-module="{{ $module->id }}"
                                       {{ $module->is_active ? 'checked' : '' }}
                                       {{ $module->is_core ? 'disabled' : '' }}>
                                <label class="custom-control-label" for="module_{{ $module->id }}"></label>
                            </div>
                        </div>
                    </div>

                    <!-- Module Features -->
                    @if($module->features && count($module->features) > 0)
                    <div class="module-features mt-3 {{ $module->is_active ? '' : 'd-none' }}">
                        <h6 class="text-muted mb-2">Features:</h6>
                        <div class="features-list">
                            @foreach($module->features as $feature)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="small mb-0" for="feature_{{ $module->id }}_{{ $feature->id }}">
                                    {{ $feature->name }}
                                </label>
                                <div class="custom-control custom-switch custom-switch-sm">
                                    <input type="checkbox"
                                           class="custom-control-input feature-toggle"
                                           id="feature_{{ $module->id }}_{{ $feature->id }}"
                                           data-module="{{ $module->id }}"
                                           data-feature="{{ $feature->id }}"
                                           {{ $feature->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="feature_{{ $module->id }}_{{ $feature->id }}"></label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Status:
                            <span class="badge badge-{{ $module->is_active ? 'success' : 'secondary' }}">
                                {{ $module->is_active ? 'Enabled' : 'Disabled' }}
                            </span>
                        </small>

                        @if($module->is_core)
                        <small class="text-info">
                            <i class="fas fa-lock"></i> Core
                        </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const moduleToggles = document.querySelectorAll('.module-toggle:not([disabled])');
    const featureToggles = document.querySelectorAll('.feature-toggle');

    // Handle module toggles
    moduleToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const moduleKey = this.dataset.module;
            const enabled = this.checked;
            const originalState = !enabled;

            // Disable toggle during request
            this.disabled = true;

            fetch('/administration/core-modules/' + moduleKey + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: enabled
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateModuleUI(this, enabled);
                    showAlert('success', data.message);
                } else {
                    // Revert toggle on error
                    this.checked = originalState;
                    showAlert('error', data.message || 'Failed to update module');
                }
            })
            .catch(error => {
                console.error('Error updating module:', error);
                // Revert toggle on error
                this.checked = originalState;
                showAlert('error', 'Error saving settings: ' + error.message);
            })
            .finally(() => {
                // Re-enable toggle
                this.disabled = false;
            });
        });
    });

    // Handle feature toggles
    featureToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const moduleKey = this.dataset.module;
            const featureKey = this.dataset.feature;
            const enabled = this.checked;
            const originalState = !enabled;

            // Disable toggle during request
            this.disabled = true;

            fetch('/administration/core-modules/' + moduleKey + '/features/' + featureKey + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: enabled
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message || 'Feature updated successfully');
                } else {
                    // Revert toggle on error
                    this.checked = originalState;
                    showAlert('error', data.message || 'Failed to update feature');
                }
            })
            .catch(error => {
                console.error('Error updating feature:', error);
                // Revert toggle on error
                this.checked = originalState;
                showAlert('error', 'Error saving settings: ' + error.message);
            })
            .finally(() => {
                // Re-enable toggle
                this.disabled = false;
            });
        });
    });

    function updateModuleUI(toggle, enabled) {
        const card = toggle.closest('.card');
        const icon = card.querySelector('.fa-2x');
        const badge = card.querySelector('.badge');
        const featuresSection = card.querySelector('.module-features');

        if (enabled) {
            card.classList.remove('border-secondary');
            card.classList.add('border-success');
            icon.classList.remove('text-muted');
            icon.classList.add('text-success');
            badge.classList.remove('badge-secondary');
            badge.classList.add('badge-success');
            badge.textContent = 'Enabled';
            if (featuresSection) {
                featuresSection.classList.remove('d-none');
            }
        } else {
            card.classList.remove('border-success');
            card.classList.add('border-secondary');
            icon.classList.remove('text-success');
            icon.classList.add('text-muted');
            badge.classList.remove('badge-success');
            badge.classList.add('badge-secondary');
            badge.textContent = 'Disabled';
            if (featuresSection) {
                featuresSection.classList.add('d-none');
            }
        }
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${iconClass} mr-2"></i>${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;

        // Remove any existing alerts
        document.querySelectorAll('.alert').forEach(alert => {
            if (!alert.classList.contains('permanent')) {
                alert.remove();
            }
        });

        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert:not(.permanent)');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
@endpush

@push('styles')
<style>
.module-card {
    transition: all 0.3s ease;
    cursor: default;
}

.module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.custom-control-input:disabled ~ .custom-control-label::before {
    background-color: #e9ecef;
    border-color: #ced4da;
}

.custom-control-input:disabled:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}

.badge {
    font-size: 0.75em;
}

.module-features {
    border-top: 1px solid #e3e6f0;
    padding-top: 0.75rem;
}

.features-list {
    max-height: 150px;
    overflow-y: auto;
}

.form-check-sm .form-check-input {
    transform: scale(0.9);
}

.form-check-sm .form-check-label {
    font-size: 0.8rem;
}

.temp-alert {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.8rem;
    z-index: 10;
}

.module-card {
    position: relative;
}

.custom-switch-sm .custom-control-input {
    transform: scale(0.8);
}

.custom-switch-sm .custom-control-label::before {
    width: 1.5rem;
    height: 0.8rem;
}

.custom-switch-sm .custom-control-label::after {
    width: 0.7rem;
    height: 0.7rem;
}

.features-list {
    max-height: 200px;
    overflow-y: auto;
    border-top: 1px solid #e9ecef;
    padding-top: 0.5rem;
}
</style>
@endpush
@endsection