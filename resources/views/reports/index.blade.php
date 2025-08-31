@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar fa-fw"></i> Reports Dashboard
        </h1>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($stats['total_revenue'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_customers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Service Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['service_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Properties</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['properties']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Row -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Reports</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.revenue') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line text-primary"></i> Revenue Analysis
                        </a>
                        <a href="{{ route('reports.compliance') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clipboard-check text-success"></i> Budget Compliance
                        </a>
                        <a href="{{ route('finance.reports.balance-sheet') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-balance-scale text-info"></i> Balance Sheet
                        </a>
                        <a href="{{ route('finance.reports.trial-balance') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calculator text-warning"></i> Trial Balance
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Operational Reports</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.service') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tools text-primary"></i> Service Performance
                        </a>
                        <a href="{{ route('reports.operational') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cogs text-success"></i> Operational Metrics
                        </a>
                        <a href="{{ route('reports.analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-area text-info"></i> Analytics Dashboard
                        </a>
                        <a href="{{ route('reports.custom') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-edit text-warning"></i> Custom Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Housing Statistics</h6>
                </div>
                <div class="card-body text-center">
                    <div class="h4 font-weight-bold text-gray-800">{{ number_format($stats['housing_applications']) }}</div>
                    <div class="text-muted">Total Applications</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Licensing Statistics</h6>
                </div>
                <div class="card-body text-center">
                    <div class="h4 font-weight-bold text-gray-800">{{ number_format($stats['licenses_issued']) }}</div>
                    <div class="text-muted">Licenses Issued</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Statistics</h6>
                </div>
                <div class="card-body text-center">
                    <div class="h4 font-weight-bold text-gray-800">{{ number_format($stats['departments']) }}</div>
                    <div class="text-muted">Active Departments</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 text-primary fw-bold">
                        <i class="fas fa-chart-bar me-2"></i>Reports Dashboard
                    </h1>
                    <p class="text-muted mb-0">Generate and view comprehensive reports across all modules</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge bg-success me-3">
                        <i class="fas fa-circle me-1"></i>All Systems Online
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Report Categories -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Financial Reports</div>
                            <div class="h4 mb-0 font-weight-bold">12</div>
                            <div class="text-xs mt-1">
                                <i class="fas fa-chart-line me-1"></i>Available templates
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Operational Reports</div>
                            <div class="h4 mb-0 font-weight-bold">8</div>
                            <div class="text-xs mt-1">
                                <i class="fas fa-cogs me-1"></i>Service metrics
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Analytics Reports</div>
                            <div class="h4 mb-0 font-weight-bold">15</div>
                            <div class="text-xs mt-1">
                                <i class="fas fa-chart-pie me-1"></i>Data insights
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-analytics fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Compliance Reports</div>
                            <div class="h4 mb-0 font-weight-bold">6</div>
                            <div class="text-xs mt-1">
                                <i class="fas fa-shield-alt me-1"></i>Regulatory compliance
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Financial Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Financial Statements
                        </a>
                        <a href="{{ route('finance.reports.balance-sheet') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-balance-scale me-2"></i>Balance Sheet
                        </a>
                        <a href="{{ route('finance.reports.revenue') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Revenue Analysis
                        </a>
                        <a href="{{ route('reports.revenue') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-coins me-2"></i>Revenue Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Operational Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.operational') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tasks me-2"></i>Service Performance
                        </a>
                        <a href="{{ route('reports.service') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Service Delivery
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock me-2"></i>Processing Times
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-area me-2"></i>Workload Analysis
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Analytics & Insights
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('reports.analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line me-2"></i>Performance Analytics
                        </a>
                        <a href="{{ route('reports.custom') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tools me-2"></i>Custom Reports
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-database me-2"></i>Data Insights
                        </a>
                        <a href="{{ route('reports.compliance') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shield-alt me-2"></i>Compliance Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Reports
                    </h5>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Generate New Report
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Generated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Monthly Financial Summary</td>
                                    <td><span class="badge bg-primary">Financial</span></td>
                                    <td>{{ now()->subDays(2)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Service Performance Report</td>
                                    <td><span class="badge bg-success">Operational</span></td>
                                    <td>{{ now()->subDays(5)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quarterly Analytics Review</td>
                                    <td><span class="badge bg-info">Analytics</span></td>
                                    <td>{{ now()->subWeek()->format('M d, Y') }}</td>
                                    <td><span class="badge bg-warning">Processing</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fas fa-clock"></i>
                                        </button>
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
