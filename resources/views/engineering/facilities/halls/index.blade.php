@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Community Halls Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.halls.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Hall
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Halls</p>
                            <h4 class="mb-0">5</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bi bi-building text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Available Now</p>
                            <h4 class="mb-0">3</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="bi bi-check-circle text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Currently Booked</p>
                            <h4 class="mb-0">2</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i class="bi bi-calendar-check text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Monthly Revenue</p>
                            <h4 class="mb-0">$2,450</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                <span class="avatar-title">
                                    <i class="bi bi-currency-dollar text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Halls List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Community Halls</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hall Name</th>
                                    <th>Capacity</th>
                                    <th>Hourly Rate</th>
                                    <th>Amenities</th>
                                    <th>Status</th>
                                    <th>Next Booking</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Main Community Hall</strong>
                                        <small class="d-block text-muted">Building A, Ground Floor</small>
                                    </td>
                                    <td>200 people</td>
                                    <td>$50/hour</td>
                                    <td>
                                        <span class="badge bg-light text-dark me-1">A/C</span>
                                        <span class="badge bg-light text-dark me-1">Audio</span>
                                        <span class="badge bg-light text-dark">Kitchen</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Available</span>
                                    </td>
                                    <td>
                                        <small>Tomorrow 2:00 PM</small>
                                        <br><small class="text-muted">Wedding Reception</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-info">Book</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Conference Room A</strong>
                                        <small class="d-block text-muted">Building B, 2nd Floor</small>
                                    </td>
                                    <td>50 people</td>
                                    <td>$25/hour</td>
                                    <td>
                                        <span class="badge bg-light text-dark me-1">A/C</span>
                                        <span class="badge bg-light text-dark me-1">Projector</span>
                                        <span class="badge bg-light text-dark">WiFi</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Booked</span>
                                    </td>
                                    <td>
                                        <small>Now - 5:00 PM</small>
                                        <br><small class="text-muted">Council Meeting</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Book</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Multi-Purpose Room</strong>
                                        <small class="d-block text-muted">Building A, 1st Floor</small>
                                    </td>
                                    <td>100 people</td>
                                    <td>$30/hour</td>
                                    <td>
                                        <span class="badge bg-light text-dark me-1">A/C</span>
                                        <span class="badge bg-light text-dark me-1">Audio</span>
                                        <span class="badge bg-light text-dark">Flexible Space</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Available</span>
                                    </td>
                                    <td>
                                        <small>Next Week Tuesday</small>
                                        <br><small class="text-muted">Community Workshop</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-info">Book</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Small Meeting Room</strong>
                                        <small class="d-block text-muted">Building B, 1st Floor</small>
                                    </td>
                                    <td>20 people</td>
                                    <td>$15/hour</td>
                                    <td>
                                        <span class="badge bg-light text-dark me-1">A/C</span>
                                        <span class="badge bg-light text-dark">WiFi</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">Maintenance</span>
                                    </td>
                                    <td>
                                        <small>Under maintenance</small>
                                        <br><small class="text-muted">Available next week</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Book</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Exhibition Hall</strong>
                                        <small class="d-block text-muted">Building C, Ground Floor</small>
                                    </td>
                                    <td>300 people</td>
                                    <td>$75/hour</td>
                                    <td>
                                        <span class="badge bg-light text-dark me-1">A/C</span>
                                        <span class="badge bg-light text-dark me-1">Audio/Visual</span>
                                        <span class="badge bg-light text-dark me-1">Stage</span>
                                        <span class="badge bg-light text-dark">Parking</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Booked</span>
                                    </td>
                                    <td>
                                        <small>This Weekend</small>
                                        <br><small class="text-muted">Art Exhibition</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Book</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Calendar Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('facilities.calendar') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar3 me-2"></i>View Calendar
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('facilities.bookings.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-list-check me-2"></i>All Bookings
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('facilities.maintenance.index') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-tools me-2"></i>Maintenance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('facilities.gate-takings.index') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-cash-coin me-2"></i>Revenue Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
