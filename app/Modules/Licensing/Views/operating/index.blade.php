@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Operating Licenses Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Operating Licenses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Applications</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['pending_applications'] }}">{{ $stats['pending_applications'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.operating.applications.index') }}?status=pending" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="bx bx-time text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Under Review</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['under_review'] }}">{{ $stats['under_review'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.operating.applications.index') }}?status=under_review" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="bx bx-search text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Require Inspection</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['requires_inspection'] }}">{{ $stats['requires_inspection'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.operating.applications.index') }}?status=requires_inspection" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="bx bx-check-shield text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Active Licenses</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['active_licenses'] }}">{{ $stats['active_licenses'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.operating.licenses.index') }}?status=active" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="bx bx-badge-check text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Expiring Soon</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['expiring_soon'] }}">{{ $stats['expiring_soon'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.operating.licenses.index') }}?expiring=30" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="bx bx-error-circle text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                $<span class="counter-value" data-target="{{ number_format($stats['total_revenue'], 0) }}">{{ number_format($stats['total_revenue'], 0) }}</span>
                            </h4>
                            <span class="text-muted">License Fees</span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="bx bx-dollar text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('licensing.operating.applications.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-2"></i>New Application
                        </a>
                        <a href="{{ route('licensing.operating.applications.index') }}" class="btn btn-outline-primary">
                            <i class="bx bx-list-ul me-2"></i>View Applications
                        </a>
                        <a href="{{ route('licensing.operating.licenses.index') }}" class="btn btn-outline-success">
                            <i class="bx bx-certificate me-2"></i>View Licenses
                        </a>
                        <a href="{{ route('licensing.operating.types.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-cog me-2"></i>License Types
                        </a>
                    </div>
                </div>
            </div>

            <!-- License Categories -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">License Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($licenseCategories as $category)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-capitalize">{{ str_replace('_', ' ', $category) }}</span>
                                <span class="badge bg-primary rounded-pill">
                                    {{ \App\Models\Licensing\OperatingLicenseType::where('category', $category)->count() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Recent Applications</h5>
                    <a href="{{ route('licensing.operating.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Application</th>
                                    <th>Business</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $application)
                                    <tr>
                                        <td>
                                            <a href="{{ route('licensing.operating.applications.show', $application) }}" class="fw-medium">
                                                {{ $application->application_number }}
                                            </a>
                                        </td>
                                        <td>{{ $application->business_name }}</td>
                                        <td>{{ $application->licenseType->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $application->status_badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No recent applications</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiring Licenses -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Expiring Licenses</h5>
                    <a href="{{ route('licensing.operating.licenses.index') }}?expiring=30" class="btn btn-sm btn-outline-warning">View All</a>
                </div>
                <div class="card-body">
                    @forelse($expiringLicenses as $license)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-16">
                                    <i class="bx bx-error-circle"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('licensing.operating.licenses.show', $license) }}" class="text-dark">
                                        {{ $license->business_name }}
                                    </a>
                                </h6>
                                <p class="text-muted mb-0">
                                    {{ $license->license_number }} - {{ $license->licenseType->name }}
                                    @if($license->isExpired())
                                        <span class="badge bg-danger ms-1">Expired</span>
                                    @else
                                        <span class="badge bg-warning ms-1">{{ $license->expiry_date->diffForHumans() }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">
                            <i class="bx bx-check-circle text-success" style="font-size: 48px;"></i>
                            <p class="text-muted">No licenses expiring soon</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const increment = target / 200;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current);
                    setTimeout(updateCounter, 10);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    });
</script>
@endpush
