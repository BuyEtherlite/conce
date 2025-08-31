@extends('layouts.admin')

@section('page-title', 'Cemetery Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>⚰️ Cemetery Management</h4>
        <div class="btn-group" role="group">
            <a href="{{ route('cemeteries.plots.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Plot
            </a>
            <a href="{{ route('cemeteries.burials.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Record Burial
            </a>
            <a href="{{ route('cemeteries.maintenance.create') }}" class="btn btn-warning">
                <i class="fas fa-plus me-1"></i>Schedule Maintenance
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Plots</h5>
                            <h2>{{ $stats['total_plots'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-th fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Available</h5>
                            <h2>{{ $stats['available_plots'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Occupied</h5>
                            <h2>{{ $stats['occupied_plots'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Reserved</h5>
                            <h2>{{ $stats['reserved_plots'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('cemeteries.plots.index') }}'">
                <div class="card-body text-center d-flex flex-column">
                    <i class="fas fa-map fa-3x text-primary mb-3"></i>
                    <h5>Plot Management</h5>
                    <p class="text-muted flex-grow-1">Manage cemetery plots, sections, and locations</p>
                    <div class="mt-auto">
                        <small class="text-muted">{{ $stats['total_plots'] }} total plots</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('cemeteries.burials.index') }}'">
                <div class="card-body text-center d-flex flex-column">
                    <i class="fas fa-book fa-3x text-success mb-3"></i>
                    <h5>Burial Records</h5>
                    <p class="text-muted flex-grow-1">Maintain burial and interment records</p>
                    <div class="mt-auto">
                        <small class="text-muted">{{ $stats['total_burials'] }} burials recorded</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('cemeteries.maintenance.index') }}'">
                <div class="card-body text-center d-flex flex-column">
                    <i class="fas fa-tools fa-3x text-warning mb-3"></i>
                    <h5>Maintenance</h5>
                    <p class="text-muted flex-grow-1">Schedule and track cemetery maintenance</p>
                    <div class="mt-auto">
                        <small class="text-muted">{{ $stats['pending_maintenance'] }} tasks pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('cemeteries.reservations.index') }}'">
                <div class="card-body text-center d-flex flex-column">
                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                    <h5>Reservations</h5>
                    <p class="text-muted flex-grow-1">Manage plot reservations and bookings</p>
                    <div class="mt-auto">
                        <small class="text-muted">{{ $stats['reserved_plots'] }} active reservations</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('cemeteries.reports.index') }}'">
                <div class="card-body text-center d-flex flex-column">
                    <i class="fas fa-chart-bar fa-3x text-secondary mb-3"></i>
                    <h5>Reports & Analytics</h5>
                    <p class="text-muted flex-grow-1">View cemetery statistics and reports</p>
                    <div class="mt-auto">
                        <small class="text-muted">Various reports available</small>
                    </div>
                </div>
            </div>
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
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
