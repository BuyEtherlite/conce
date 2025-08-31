@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Engineering Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('engineering.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.projects.index') }}">
                            <i class="fas fa-project-diagram"></i> Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.facilities.index') }}">
                            <i class="fas fa-building"></i> Facilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.inspections.index') }}">
                            <i class="fas fa-search"></i> Inspections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.infrastructure.index') }}">
                            <i class="fas fa-road"></i> Infrastructure
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.maintenance.index') }}">
                            <i class="fas fa-wrench"></i> Maintenance
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Engineering Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('engineering.projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Project
                    </a>
                </div>
            </div>

            <!-- Dashboard content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Active Projects</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-primary">{{ \App\Modules\Engineering\Models\EngineeringProject::count() }}</h2>
                            <p class="text-muted">Ongoing Projects</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Facilities</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Facilities\Facility::count() }}</h2>
                            <p class="text-muted">Total Facilities</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Inspections</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ \App\Models\Engineering\BuildingInspection::count() }}</h2>
                            <p class="text-muted">Completed Inspections</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Engineering Services</h4>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Projects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Work Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Maintenance Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">15</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Planning Applications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">6</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Categories -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Project Management</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('engineering.projects.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-project-diagram me-2"></i>
                                Engineering Projects
                            </div>
                            <span class="badge bg-primary rounded-pill">12</span>
                        </a>
                        <a href="{{ route('engineering.work-orders.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-clipboard-list me-2"></i>
                                Work Orders
                            </div>
                            <span class="badge bg-success rounded-pill">8</span>
                        </a>
                        <a href="{{ route('engineering.surveys.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-map-marked-alt me-2"></i>
                                Surveys
                            </div>
                            <span class="badge bg-info rounded-pill">5</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Infrastructure & Planning</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('engineering.infrastructure.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-road me-2"></i>
                                Infrastructure Management
                            </div>
                            <span class="badge bg-warning rounded-pill">25</span>
                        </a>
                        <a href="{{ route('engineering.planning.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-map me-2"></i>
                                Town Planning
                            </div>
                            <span class="badge bg-secondary rounded-pill">6</span>
                        </a>
                        <a href="{{ route('engineering.maintenance.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-wrench me-2"></i>
                                Maintenance
                            </div>
                            <span class="badge bg-danger rounded-pill">15</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('Y-m-d') }}</td>
                                    <td>Road Maintenance - Main Street</td>
                                    <td>Work Order</td>
                                    <td><span class="badge bg-warning">In Progress</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime('-1 day')) }}</td>
                                    <td>Water Pipeline Extension</td>
                                    <td>Project</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime('-2 days')) }}</td>
                                    <td>Building Plan Approval</td>
                                    <td>Planning</td>
                                    <td><span class="badge bg-info">Under Review</span></td>
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