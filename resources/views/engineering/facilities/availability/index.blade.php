@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facility Availability</h4>
                <div class="page-title-right">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active">Today</button>
                        <button type="button" class="btn btn-outline-primary">This Week</button>
                        <button type="button" class="btn btn-outline-primary">This Month</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Availability Overview -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">5</h5>
                    <p class="card-text">Available Now</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">3</h5>
                    <p class="card-text">Currently Booked</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">1</h5>
                    <p class="card-text">Under Maintenance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">9</h5>
                    <p class="card-text">Total Facilities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Availability -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Real-Time Facility Status</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Facility</th>
                                    <th>Type</th>
                                    <th>Current Status</th>
                                    <th>Next Available</th>
                                    <th>Current Booking</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-success">
                                    <td><strong>Olympic Pool</strong></td>
                                    <td>Swimming Pool</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>Now</td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Book Now</button>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>Basketball Court</strong></td>
                                    <td>Sports Facility</td>
                                    <td><span class="badge bg-warning">Booked</span></td>
                                    <td>4:00 PM</td>
                                    <td>Youth Training (2:00 PM - 4:00 PM)</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info">View Schedule</button>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Tennis Court 1</strong></td>
                                    <td>Sports Facility</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>Now</td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Book Now</button>
                                    </td>
                                </tr>
                                <tr class="table-danger">
                                    <td><strong>Therapy Pool</strong></td>
                                    <td>Swimming Pool</td>
                                    <td><span class="badge bg-danger">Maintenance</span></td>
                                    <td>Tomorrow 8:00 AM</td>
                                    <td>Filter Replacement</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Unavailable</button>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Main Community Hall</strong></td>
                                    <td>Community Hall</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>Now</td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Book Now</button>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>Conference Room A</strong></td>
                                    <td>Community Hall</td>
                                    <td><span class="badge bg-warning">Booked</span></td>
                                    <td>6:00 PM</td>
                                    <td>Board Meeting (1:00 PM - 5:00 PM)</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info">View Schedule</button>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Football Field</strong></td>
                                    <td>Sports Facility</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>Now</td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Book Now</button>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>Cricket Pitch</strong></td>
                                    <td>Sports Facility</td>
                                    <td><span class="badge bg-warning">Booked</span></td>
                                    <td>8:00 PM</td>
                                    <td>Club Practice (6:00 PM - 8:00 PM)</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info">View Schedule</button>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Multi-Purpose Room</strong></td>
                                    <td>Community Hall</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>Now</td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Book Now</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
