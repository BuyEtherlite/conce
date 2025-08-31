@extends('layouts.app')

@section('page-title', 'Facility Bookings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facility Management</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>New Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-building display-4 text-primary"></i>
                    <h5 class="mt-2">9</h5>
                    <p class="text-muted">Total Facilities</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-check display-4 text-success"></i>
                    <h5 class="mt-2">15</h5>
                    <p class="text-muted">Bookings Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-currency-dollar display-4 text-warning"></i>
                    <h5 class="mt-2">$2,450</h5>
                    <p class="text-muted">Revenue Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-tools display-4 text-danger"></i>
                    <h5 class="mt-2">2</h5>
                    <p class="text-muted">Under Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Facility Categories -->
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.pools') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-water display-4 text-primary mb-3"></i>
                    <h5>Swimming Pools</h5>
                    <p class="text-muted">Olympic Pool, Children's Pool, Therapy Pool</p>
                    <div class="mt-3">
                        <span class="badge bg-success me-2">2 Available</span>
                        <span class="badge bg-danger">1 Maintenance</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.halls') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-building display-4 text-warning mb-3"></i>
                    <h5>Community Halls</h5>
                    <p class="text-muted">Main Hall, Conference Rooms, Multi-Purpose Room</p>
                    <div class="mt-3">
                        <span class="badge bg-success me-2">2 Available</span>
                        <span class="badge bg-warning">1 Booked</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.sports') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-trophy display-4 text-success mb-3"></i>
                    <h5>Sports Facilities</h5>
                    <p class="text-muted">Tennis Courts, Basketball, Football Field, Cricket</p>
                    <div class="mt-3">
                        <span class="badge bg-success me-2">2 Available</span>
                        <span class="badge bg-warning">2 Booked</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Tools -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.calendar') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-calendar3 display-4 text-info mb-3"></i>
                    <h5>Calendar View</h5>
                    <p class="text-muted">See all bookings in calendar format</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.schedule.index') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 text-secondary mb-3"></i>
                    <h5>Weekly Schedule</h5>
                    <p class="text-muted">View regular facility schedules</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.availability.index') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                    <h5>Availability</h5>
                    <p class="text-muted">Real-time facility availability</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.maintenance.index') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-gear display-4 text-danger mb-3"></i>
                    <h5>Maintenance</h5>
                    <p class="text-muted">Facility maintenance tracking</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Gate Takings -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card module-card h-100" onclick="location.href='{{ route('facilities.gate-takings.index') }}'">
                <div class="card-body text-center">
                    <i class="bi bi-cash-coin display-4 text-primary mb-3"></i>
                    <h5>Gate Takings</h5>
                    <p class="text-muted">Record and manage facility revenue</p>
                    <div class="mt-3">
                        <h6 class="text-primary">Today: $1,065.00</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card module-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up display-4 text-success mb-3"></i>
                    <h5>Usage Statistics</h5>
                    <p class="text-muted">Facility utilization analytics</p>
                    <div class="mt-3">
                        <h6 class="text-success">85% Utilization Rate</h6>
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
