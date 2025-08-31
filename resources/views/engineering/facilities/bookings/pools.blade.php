@extends('layouts.app')

@section('page-title', 'Pool Bookings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏊‍♂️ Pool Bookings</h4>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Pool Booking
        </button>
    </div>

    <!-- Pool Booking Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-swimming-pool fa-2x text-primary mb-2"></i>
                    <h5>Today's Bookings</h5>
                    <h3 class="text-primary">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5>Available Slots</h5>
                    <h3 class="text-warning">8</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-week fa-2x text-success mb-2"></i>
                    <h5>This Week</h5>
                    <h3 class="text-success">15</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                    <h5>Occupancy Rate</h5>
                    <h3 class="text-info">75%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Calendar/Schedule -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pool Schedule - Today</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Pool A</th>
                            <th>Pool B</th>
                            <th>Pool C</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>06:00 - 08:00</strong></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-danger">Booked</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                        </tr>
                        <tr>
                            <td><strong>08:00 - 10:00</strong></td>
                            <td><span class="badge bg-warning">Maintenance</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-danger">Booked</span></td>
                        </tr>
                        <tr>
                            <td><strong>10:00 - 12:00</strong></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                        </tr>
                        <tr>
                            <td><strong>12:00 - 14:00</strong></td>
                            <td><span class="badge bg-danger">Booked</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                        </tr>
                        <tr>
                            <td><strong>14:00 - 16:00</strong></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-danger">Booked</span></td>
                            <td><span class="badge bg-warning">Maintenance</span></td>
                        </tr>
                        <tr>
                            <td><strong>16:00 - 18:00</strong></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Recent Pool Bookings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Pool</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#PB001</strong></td>
                            <td>John Smith</td>
                            <td>Pool A</td>
                            <td>{{ date('Y-m-d') }}</td>
                            <td>08:00 - 10:00</td>
                            <td><span class="badge bg-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#PB002</strong></td>
                            <td>Sarah Johnson</td>
                            <td>Pool C</td>
                            <td>{{ date('Y-m-d') }}</td>
                            <td>10:00 - 12:00</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#PB003</strong></td>
                            <td>Mike Davis</td>
                            <td>Pool B</td>
                            <td>{{ date('Y-m-d', strtotime('+1 day')) }}</td>
                            <td>14:00 - 16:00</td>
                            <td><span class="badge bg-info">Scheduled</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-body i.fa-2x {
    opacity: 0.8;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection
