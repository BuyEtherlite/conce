@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Business Licensing Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Licensing</li>
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
                            <a href="{{ route('licensing.applications.index') }}" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="bx bx-time-five text-warning"></i>
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
                            <a href="{{ route('licensing.applications.index') }}?status=under_review" class="text-decoration-underline">View All</a>
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
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Requires Inspection</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['requires_inspection'] }}">{{ $stats['requires_inspection'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.inspections.index') }}" class="text-decoration-underline">View All</a>
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
                            <a href="{{ route('licensing.licenses.index') }}" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="bx bx-id-card text-success"></i>
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
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Expired Licenses</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $stats['expired_licenses'] }}">{{ $stats['expired_licenses'] }}</span>
                            </h4>
                            <a href="{{ route('licensing.licenses.index') }}?status=expired" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                <i class="bx bx-error text-danger"></i>
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
                                $<span class="counter-value" data-target="{{ $stats['total_revenue'] }}">{{ number_format($stats['total_revenue'], 0) }}</span>
                            </h4>
                            <span class="text-muted">License Fees</span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                <i class="bx bx-dollar text-secondary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('licensing.applications.create') }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-plus me-2"></i>New Application
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('licensing.applications.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-list me-2"></i>All Applications
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('licensing.licenses.index') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-id-card me-2"></i>All Licenses
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('licensing.inspections.index') }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-search me-2"></i>Inspections
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <!-- Recent Applications -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent License Applications</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Application</th>
                                            <th>License Type</th>
                                            <th>Applicant</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentApplications as $application)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('licensing.applications.show', $application) }}" class="text-decoration-none">
                                                        {{ $application->application_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $application->licenseType->name }}</td>
                                                <td>{{ $application->applicant_name }}</td>
                                                <td>
                                                    <span class="badge {{ $application->status_badge }}">
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

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Shop Permit Applications</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Application</th>
                                            <th>Business Name</th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentShopApplications as $application)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('licensing.shop-permits.show', $application) }}" class="text-decoration-none">
                                                        {{ $application->application_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $application->business_name }}</td>
                                                <td>{{ $application->owner_name }}</td>
                                                <td>
                                                    <span class="badge {{ $application->status_badge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No recent shop applications</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Expiring Licenses -->
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Expiring Licenses</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('licensing.licenses.index') }}" class="btn btn-soft-warning btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($expiringLicenses as $license)
                        <div class="d-flex border-bottom pb-3 mb-3">
                            <div class="avatar-xs flex-shrink-0 me-3">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-16">
                                    <i class="bx bx-id-card"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $license->license_number }}</h6>
                                <p class="text-muted mb-1">{{ $license->business_name }}</p>
                                <p class="text-muted mb-0 fs-12">
                                    <span class="text-dark">Expires:</span> {{ $license->expiry_date->format('M d, Y') }}
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

            <!-- Workflow Process -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Licensing Workflow</h5>
                </div>
                <div class="card-body">
                    <div class="process-steps">
                        <div class="step">
                            <div class="step-icon bg-primary text-white">1</div>
                            <div class="step-content">
                                <h6 class="step-title">Application Submitted</h6>
                                <p class="step-description">Applicant submits license application with required documents</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-icon bg-info text-white">2</div>
                            <div class="step-content">
                                <h6 class="step-title">Under Review</h6>
                                <p class="step-description">Application reviewed by licensing officer</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-icon bg-warning text-white">3</div>
                            <div class="step-content">
                                <h6 class="step-title">Inspection (if required)</h6>
                                <p class="step-description">Site inspection conducted by qualified inspector</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-icon bg-success text-white">4</div>
                            <div class="step-content">
                                <h6 class="step-title">License Issued</h6>
                                <p class="step-description">License approved and issued to applicant</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.process-steps {
    position: relative;
}

.step {
    display: flex;
    align-items: start;
    margin-bottom: 20px;
    position: relative;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 32px;
    width: 2px;
    height: 30px;
    background-color: #e9ecef;
    z-index: 0;
}

.step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 12px;
    position: relative;
    z-index: 1;
}

.step-content {
    flex: 1;
}

.step-title {
    margin-bottom: 4px;
    font-size: 14px;
}

.step-description {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endsection