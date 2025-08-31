@extends('layouts.admin')

@section('title', 'Parking Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-car text-primary"></i>
            Parking Management
        </h1>
        <div>
            <a href="{{ route('parking.violations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Issue Violation
            </a>
            <a href="{{ route('parking.zones.create') }}" class="btn btn-success">
                <i class="fas fa-map-marked-alt"></i> Add Zone
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Zones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_zones']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available Spaces
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['available_spaces']) }} / {{ number_format($stats['total_spaces']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-parking fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Active Violations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['active_violations']) }}
                            </div>
                            <small class="text-danger">{{ number_format($stats['overdue_violations']) }} overdue</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                R{{ number_format($stats['today_revenue'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Violations -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Violations</h6>
                    <a href="{{ route('parking.violations.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentViolations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Violation #</th>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Zone</th>
                                        <th>Fine</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentViolations as $violation)
                                        <tr>
                                            <td>
                                                <a href="{{ route('parking.violations.show', $violation) }}" class="text-primary">
                                                    {{ $violation->violation_number }}
                                                </a>
                                            </td>
                                            <td>{{ $violation->vehicle_registration }}</td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $violation->getViolationTypeLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $violation->zone->zone_name }}</td>
                                            <td>R{{ number_format($violation->fine_amount, 2) }}</td>
                                            <td>
                                                @switch($violation->status)
                                                    @case('paid')
                                                        <span class="badge badge-success">Paid</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="badge badge-danger">Overdue</span>
                                                        @break
                                                    @case('disputed')
                                                        <span class="badge badge-warning">Disputed</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-primary">{{ ucfirst($violation->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $violation->violation_datetime->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No violations recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Zone Statistics -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Zone Statistics</h6>
                    <a href="{{ route('parking.zones.index') }}" class="btn btn-sm btn-primary">Manage Zones</a>
                </div>
                <div class="card-body">
                    @foreach($zoneStats as $zone)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm font-weight-bold">{{ $zone->zone_name }}</span>
                                <span class="text-sm">{{ $zone->violations_count }} violations</span>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $zoneStats->max('violations_count') > 0 ? ($zone->violations_count / $zoneStats->max('violations_count')) * 100 : 0 }}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ ucfirst($zone->zone_type) }} - R{{ number_format($zone->hourly_rate, 2) }}/hour
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('parking.violations.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus text-primary"></i> Issue New Violation
                        </a>
                        <a href="{{ route('parking.violations.index', ['status' => 'overdue']) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock text-danger"></i> View Overdue Violations
                        </a>
                        <a href="{{ route('parking.reports') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar text-success"></i> Generate Reports
                        </a>
                        <a href="{{ route('parking.zones.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marked-alt text-info"></i> Create New Zone
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
