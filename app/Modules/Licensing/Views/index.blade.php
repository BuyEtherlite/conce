@extends('layouts.admin')

@section('title', 'Licensing & Permits')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-certificate me-2"></i>
                        Licensing & Business Permits
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Active Licenses</h6>
                                            <h4>{{ $stats['active_licenses'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-certificate fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Pending Applications</h6>
                                            <h4>{{ $stats['pending_applications'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Expiring Soon</h6>
                                            <h4>{{ $stats['expiring_soon'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Revenue (Monthly)</h6>
                                            <h4>${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h4>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">License & Permit Services</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('licensing.applications.index') }}" class="btn btn-outline-primary w-100 py-3">
                                                <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                                <div>License Applications</div>
                                                <small class="text-muted">Process new applications</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('licensing.licenses.index') }}" class="btn btn-outline-success w-100 py-3">
                                                <i class="fas fa-certificate fa-2x mb-2 d-block"></i>
                                                <div>Active Licenses</div>
                                                <small class="text-muted">Manage existing licenses</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('licensing.shop-permits.index') }}" class="btn btn-outline-info w-100 py-3">
                                                <i class="fas fa-store fa-2x mb-2 d-block"></i>
                                                <div>Shop Permits</div>
                                                <small class="text-muted">Business operating permits</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('licensing.operating.index') }}" class="btn btn-outline-warning w-100 py-3">
                                                <i class="fas fa-industry fa-2x mb-2 d-block"></i>
                                                <div>Operating Licenses</div>
                                                <small class="text-muted">Industrial & commercial</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Applications -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Recent Applications</h6>
                                    <a href="{{ route('licensing.applications.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i> New Application
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Application ID</th>
                                                    <th>Business Name</th>
                                                    <th>License Type</th>
                                                    <th>Status</th>
                                                    <th>Submitted</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentApplications ?? [] as $application)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $application->application_number ?? 'LA-' . str_pad($application->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                                    </td>
                                                    <td>{{ $application->business_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $application->license_type ?? 'General' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ 
                                                            $application->status === 'approved' ? 'success' : 
                                                            ($application->status === 'pending' ? 'warning' : 
                                                            ($application->status === 'under_review' ? 'primary' : 'danger')) 
                                                        }}">
                                                            {{ ucfirst(str_replace('_', ' ', $application->status ?? 'pending')) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $application->created_at ? $application->created_at->format('M d, Y') : 'N/A' }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('licensing.applications.show', $application->id) }}" class="btn btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($application->status === 'pending')
                                                            <button class="btn btn-outline-success" onclick="reviewApplication({{ $application->id }})" title="Review">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">No applications found.</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Expiring Licenses</h6>
                                </div>
                                <div class="card-body">
                                    @forelse($expiringLicenses ?? [] as $license)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                        <div>
                                            <div class="fw-bold">{{ $license->business_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $license->license_type ?? 'General' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="badge bg-warning">
                                                {{ $license->expires_at ? $license->expires_at->diffForHumans() : 'Unknown' }}
                                            </div>
                                            <div>
                                                <a href="{{ route('licensing.licenses.renew', $license->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                    Renew
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-3">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <p class="text-muted mb-0">No licenses expiring soon.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Stats</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Approval Rate</span>
                                            <strong>{{ $stats['approval_rate'] ?? 0 }}%</strong>
                                        </div>
                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-success" style="width: {{ $stats['approval_rate'] ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Avg. Processing Time</span>
                                            <strong>{{ $stats['avg_processing_days'] ?? 0 }} days</strong>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <span>YTD Revenue</span>
                                            <strong>${{ number_format($stats['ytd_revenue'] ?? 0, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reviewApplication(applicationId) {
    // Implementation for reviewing application
    alert('Application review feature coming soon!');
}
</script>
@endpush
@endsection