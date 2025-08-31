@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🏥 Health Management</h1>
    </div>

    <!-- Health Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Health Facilities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_facilities'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Permits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_permits'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Inspections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_inspections'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Health Practitioners</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['registered_practitioners'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Management Modules -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.facilities.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-hospital fa-3x text-primary mb-3"></i>
                    <h5>Health Facilities</h5>
                    <p class="text-muted">Manage health facilities and clinics</p>
                    <small class="text-info">{{ $stats['total_facilities'] }} facilities</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.permits.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-certificate fa-3x text-success mb-3"></i>
                    <h5>Health Permits</h5>
                    <p class="text-muted">Health permits and licenses</p>
                    <small class="text-success">{{ $stats['active_permits'] }} active permits</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.inspections.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-check fa-3x text-info mb-3"></i>
                    <h5>Health Inspections</h5>
                    <p class="text-muted">Schedule and manage inspections</p>
                    <small class="text-info">{{ $stats['pending_inspections'] }} pending</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.practitioners.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-3x text-warning mb-3"></i>
                    <h5>Health Practitioners</h5>
                    <p class="text-muted">Registered health professionals</p>
                    <small class="text-warning">{{ $stats['registered_practitioners'] }} practitioners</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.environmental.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                    <h5>Environmental Health</h5>
                    <p class="text-muted">Environmental health services</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.food-safety.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                    <h5>Food Safety</h5>
                    <p class="text-muted">Food safety and hygiene</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.immunization.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-syringe fa-3x text-info mb-3"></i>
                    <h5>Immunization</h5>
                    <p class="text-muted">Vaccination programs</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.records.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-file-medical fa-3x text-secondary mb-3"></i>
                    <h5>Health Records</h5>
                    <p class="text-muted">Health data and records</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('health.emergency.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-ambulance fa-3x text-danger mb-3"></i>
                    <h5>Emergency Services</h5>
                    <p class="text-muted">Emergency health response</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Permit Applications</h6>
                </div>
                <div class="card-body">
                    @if($stats['expired_permits'] > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $stats['expired_permits'] }} permits have expired and need renewal.
                        </div>
                    @endif
                    <p class="text-muted">Recent health permit activity will be displayed here.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Inspection Schedule</h6>
                </div>
                <div class="card-body">
                    @if($stats['completed_inspections'] > 0)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ $stats['completed_inspections'] }} inspections completed this month.
                        </div>
                    @endif
                    <p class="text-muted">Upcoming health inspections will be displayed here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.module-card {
    transition: transform 0.2s;
    cursor: pointer;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
}
</style>
@endsection
