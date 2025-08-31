@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facilities Calendar</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i>New Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Facility Bookings Calendar</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active">Month</button>
                            <button type="button" class="btn btn-outline-primary">Week</button>
                            <button type="button" class="btn btn-outline-primary">Day</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="calendar-placeholder bg-light p-5 text-center">
                        <i class="bi bi-calendar3 display-1 text-muted"></i>
                        <h4 class="mt-3">Calendar View</h4>
                        <p class="text-muted">Interactive calendar showing all facility bookings would be displayed here.</p>
                        
                        <!-- Sample booking entries -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Today's Bookings</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>10:00 AM</strong> - Basketball Court</li>
                                            <li><strong>2:00 PM</strong> - Tennis Court 1</li>
                                            <li><strong>6:00 PM</strong> - Main Hall</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">Tomorrow's Bookings</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>9:00 AM</strong> - Football Field</li>
                                            <li><strong>11:00 AM</strong> - Olympic Pool</li>
                                            <li><strong>4:00 PM</strong> - Conference Room A</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">Upcoming Events</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>This Weekend</strong> - Swimming Gala</li>
                                            <li><strong>Next Week</strong> - Basketball Tournament</li>
                                            <li><strong>Month End</strong> - Community Fair</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6>Legend</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <span><i class="bi bi-square-fill text-primary"></i> Swimming Pools</span>
                        <span><i class="bi bi-square-fill text-success"></i> Sports Facilities</span>
                        <span><i class="bi bi-square-fill text-warning"></i> Community Halls</span>
                        <span><i class="bi bi-square-fill text-danger"></i> Maintenance</span>
                        <span><i class="bi bi-square-fill text-info"></i> Events</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
