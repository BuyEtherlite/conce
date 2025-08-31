@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facilities Schedule</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Sunday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>6:00 AM</strong></td>
                                    <td><span class="badge bg-primary">Pool - Swimming Class</span></td>
                                    <td></td>
                                    <td><span class="badge bg-primary">Pool - Swimming Class</span></td>
                                    <td></td>
                                    <td><span class="badge bg-primary">Pool - Swimming Class</span></td>
                                    <td><span class="badge bg-success">Sports - Tennis</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>8:00 AM</strong></td>
                                    <td><span class="badge bg-success">Sports - Tennis</span></td>
                                    <td><span class="badge bg-warning">Hall - Yoga</span></td>
                                    <td><span class="badge bg-success">Sports - Tennis</span></td>
                                    <td><span class="badge bg-warning">Hall - Yoga</span></td>
                                    <td><span class="badge bg-success">Sports - Tennis</span></td>
                                    <td><span class="badge bg-info">Event - Community</span></td>
                                    <td><span class="badge bg-primary">Pool - Open Swim</span></td>
                                </tr>
                                <tr>
                                    <td><strong>10:00 AM</strong></td>
                                    <td><span class="badge bg-warning">Hall - Meeting</span></td>
                                    <td><span class="badge bg-success">Sports - Basketball</span></td>
                                    <td><span class="badge bg-warning">Hall - Meeting</span></td>
                                    <td><span class="badge bg-success">Sports - Basketball</span></td>
                                    <td><span class="badge bg-warning">Hall - Meeting</span></td>
                                    <td><span class="badge bg-info">Event - Market Day</span></td>
                                    <td><span class="badge bg-success">Sports - Football</span></td>
                                </tr>
                                <tr>
                                    <td><strong>2:00 PM</strong></td>
                                    <td><span class="badge bg-primary">Pool - Therapy</span></td>
                                    <td><span class="badge bg-warning">Hall - Conference</span></td>
                                    <td><span class="badge bg-primary">Pool - Therapy</span></td>
                                    <td><span class="badge bg-warning">Hall - Conference</span></td>
                                    <td><span class="badge bg-primary">Pool - Therapy</span></td>
                                    <td><span class="badge bg-success">Sports - Cricket</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>4:00 PM</strong></td>
                                    <td><span class="badge bg-success">Sports - Youth Training</span></td>
                                    <td><span class="badge bg-primary">Pool - Kids Class</span></td>
                                    <td><span class="badge bg-success">Sports - Youth Training</span></td>
                                    <td><span class="badge bg-primary">Pool - Kids Class</span></td>
                                    <td><span class="badge bg-success">Sports - Youth Training</span></td>
                                    <td><span class="badge bg-warning">Hall - Wedding</span></td>
                                    <td><span class="badge bg-info">Event - Festival</span></td>
                                </tr>
                                <tr>
                                    <td><strong>6:00 PM</strong></td>
                                    <td><span class="badge bg-warning">Hall - Community Group</span></td>
                                    <td><span class="badge bg-success">Sports - Adult League</span></td>
                                    <td><span class="badge bg-warning">Hall - Community Group</span></td>
                                    <td><span class="badge bg-success">Sports - Adult League</span></td>
                                    <td><span class="badge bg-warning">Hall - Community Group</span></td>
                                    <td><span class="badge bg-info">Event - Concert</span></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6>Schedule Legend</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <span><span class="badge bg-primary"></span> Swimming Pool Activities</span>
                        <span><span class="badge bg-success"></span> Sports Facilities</span>
                        <span><span class="badge bg-warning"></span> Community Halls</span>
                        <span><span class="badge bg-info"></span> Special Events</span>
                        <span><span class="badge bg-danger"></span> Maintenance Period</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
