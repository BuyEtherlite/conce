@extends('layouts.app')

@section('title', 'Infrastructure Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Infrastructure Management</h1>
                <p class="page-subtitle">Manage municipal infrastructure and public works</p>
            </div>
        </div>
    </div>

    <!-- Infrastructure Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Projects</div>
                            <div class="h4 mb-0 font-weight-bold">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hammer fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Maintenance Orders</div>
                            <div class="h4 mb-0 font-weight-bold">24</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Budget Utilized</div>
                            <div class="h4 mb-0 font-weight-bold">R2.1M</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Approvals</div>
                            <div class="h4 mb-0 font-weight-bold">6</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Infrastructure Categories -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-road fa-3x text-primary mb-3"></i>
                    <h5>Roads & Pavements</h5>
                    <p class="text-muted">Manage road maintenance and construction projects</p>
                    <a href="#" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-bridge fa-3x text-success mb-3"></i>
                    <h5>Bridges & Structures</h5>
                    <p class="text-muted">Monitor and maintain municipal structures</p>
                    <a href="#" class="btn btn-success">Manage</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-3x text-info mb-3"></i>
                    <h5>Public Works</h5>
                    <p class="text-muted">Coordinate public infrastructure projects</p>
                    <a href="#" class="btn btn-info">Manage</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Infrastructure Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Budget</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Main Street Resurfacing</td>
                                    <td>Road Maintenance</td>
                                    <td><span class="badge bg-warning">In Progress</span></td>
                                    <td>R450,000</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 65%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Community Center Renovation</td>
                                    <td>Building Maintenance</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>R1,200,000</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Water Pipe Installation</td>
                                    <td>Utility Infrastructure</td>
                                    <td><span class="badge bg-info">Planning</span></td>
                                    <td>R800,000</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 15%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
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
