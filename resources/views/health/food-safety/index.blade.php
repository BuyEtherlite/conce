@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Food Safety Management</h1>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fas fa-plus"></i> New Entry
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#"><i class="fas fa-store"></i> Register Establishment</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-certificate"></i> Food Handler Permit</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-search"></i> Schedule Inspection</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Registered Establishments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $foodSafetyData['registered_establishments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Inspections Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $foodSafetyData['inspections_completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Violations Found</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $foodSafetyData['violations_found'] }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Average Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $foodSafetyData['average_inspection_score'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Inspections -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Inspections</h6>
                    <a href="{{ route('health.inspections.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Establishment</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>City Restaurant</td>
                                    <td>2025-01-08</td>
                                    <td><span class="badge badge-success">92%</span></td>
                                    <td><span class="badge badge-success">Passed</span></td>
                                </tr>
                                <tr>
                                    <td>Corner Bakery</td>
                                    <td>2025-01-07</td>
                                    <td><span class="badge badge-warning">78%</span></td>
                                    <td><span class="badge badge-warning">Conditional</span></td>
                                </tr>
                                <tr>
                                    <td>Fast Food Plaza</td>
                                    <td>2025-01-06</td>
                                    <td><span class="badge badge-success">95%</span></td>
                                    <td><span class="badge badge-success">Passed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Food Handler Permits -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Food Handler Permits</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="border-right">
                                <div class="h4 font-weight-bold text-success">{{ $foodSafetyData['food_handler_permits'] }}</div>
                                <div class="small text-muted">Active Permits</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="h4 font-weight-bold text-warning">45</div>
                            <div class="small text-muted">Expiring Soon</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="btn btn-primary btn-sm">Issue New Permit</a>
                        <a href="#" class="btn btn-secondary btn-sm">Renewal Notices</a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-triangle text-danger"></i> Emergency Closure Notice
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-clipboard-check text-success"></i> Compliance Verification
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-alt text-info"></i> Generate Inspection Report
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell text-warning"></i> Send Renewal Reminders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
