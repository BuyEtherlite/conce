@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users fa-fw"></i> Service Request Management
        </h1>
        <div class="d-none d-lg-inline-block">
            <a href="{{ route('public-services.requests.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> New Service Request
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['in_progress_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['completed_today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['overdue_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Emergency</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['emergency_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Resolution (Days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['avg_resolution_time'], 1) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Satisfaction Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['satisfaction_rate'], 1) }}/5.0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Requests -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="{{ route('public-services.requests') }}">View All</a>
                            <a class="dropdown-item" href="{{ route('public-services.analytics') }}">Analytics</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Customer</th>
                                        <th>Service Type</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td>
                                            <a href="{{ route('public-services.requests.show', $request) }}" class="text-decoration-none">
                                                {{ $request->request_number }}
                                                @if($request->is_emergency)
                                                    <i class="fas fa-fire text-danger ml-1" title="Emergency"></i>
                                                @endif
                                            </a>
                                        </td>
                                        <td>{{ $request->customer->full_name ?? 'N/A' }}</td>
                                        <td>{{ $request->serviceType->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $request->priority_badge_color }}">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $request->status_badge_color }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->requested_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No service requests yet.</p>
                            <a href="{{ route('public-services.requests.create') }}" class="btn btn-primary">
                                Create First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Distribution</h6>
                </div>
                <div class="card-body">
                    @if($statusDistribution->count() > 0)
                        @foreach($statusDistribution as $status => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            <span class="badge badge-primary">{{ $count }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No data available</p>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Priority Distribution</h6>
                </div>
                <div class="card-body">
                    @if($priorityDistribution->count() > 0)
                        @foreach($priorityDistribution as $priority => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-capitalize">{{ $priority }}</span>
                            <span class="badge badge-secondary">{{ $count }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public-services.requests.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> New Request
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public-services.requests') }}?status=submitted" class="btn btn-warning btn-block">
                                <i class="fas fa-clock"></i> Pending Requests
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public-services.requests') }}?is_emergency=1" class="btn btn-danger btn-block">
                                <i class="fas fa-fire"></i> Emergency Requests
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public-services.analytics') }}" class="btn btn-info btn-block">
                                <i class="fas fa-chart-bar"></i> Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
