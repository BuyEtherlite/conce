@extends('layouts.admin')

@section('page-title', 'Town Planning Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-map me-2"></i>Town Planning Dashboard</h4>
        <div>
            <a href="{{ route('planning.applications.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> New Application
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Applications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_applications'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('planning.applications.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                    <h5>Planning Applications</h5>
                    <p class="text-muted">Manage development applications and permits</p>
                    <div class="mt-3">
                        <span class="badge badge-primary">{{ $stats['total_applications'] }} Applications</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('planning.approvals.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Approvals & Permits</h5>
                    <p class="text-muted">Issue permits and manage approvals</p>
                    <div class="mt-3">
                        <span class="badge badge-success">{{ $stats['approved'] }} Approved</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('planning.zoning.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-map-marked-alt fa-3x text-warning mb-3"></i>
                    <h5>Zoning & Land Use</h5>
                    <p class="text-muted">Zoning maps, regulations and land use planning</p>
                    <div class="mt-3">
                        <span class="badge badge-info">3 Zones</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Planning Applications</h6>
        </div>
        <div class="card-body">
            @if(isset($recent_applications) && $recent_applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Application #</th>
                                <th>Applicant</th>
                                <th>Property</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_applications as $application)
                            <tr>
                                <td>{{ $application->application_number }}</td>
                                <td>{{ $application->applicant_name }}</td>
                                <td>{{ Str::limit($application->property_address, 50) }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ ucfirst(str_replace('_', ' ', $application->application_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $application->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>{{ $application->date_submitted->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('planning.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('planning.applications.index') }}" class="btn btn-primary">
                        View All Applications
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Applications Found</h5>
                    <p class="text-muted">Start by creating your first planning application.</p>
                    <a href="{{ route('planning.applications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Application
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.module-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
</style>
@endsection
