@extends('layouts.app')

@section('title', 'Analytics Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Analytics Reports</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Revenue Analysis</div>
                                    <div class="text-xs mb-1 text-uppercase text-primary">Financial Performance</div>
                                    <div class="small text-muted">Detailed revenue trends and analysis</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('reports.analytics.revenue-analysis') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Performance Metrics</div>
                                    <div class="text-xs mb-1 text-uppercase text-success">System Performance</div>
                                    <div class="small text-muted">Key performance indicators and metrics</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('reports.analytics.performance-metrics') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> View Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Data Insights</div>
                                    <div class="text-xs mb-1 text-uppercase text-info">Business Intelligence</div>
                                    <div class="small text-muted">Advanced analytics and insights</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-brain fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-info btn-sm" disabled>
                                    <i class="fas fa-cog"></i> Coming Soon
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Available Analytics Reports</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Report Name</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Revenue Analysis</td>
                                            <td>Comprehensive revenue trends, forecasting, and performance analysis</td>
                                            <td><span class="badge badge-primary">Financial</span></td>
                                            <td>
                                                <a href="{{ route('reports.analytics.revenue-analysis') }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Performance Metrics</td>
                                            <td>System performance indicators and operational efficiency metrics</td>
                                            <td><span class="badge badge-success">Operations</span></td>
                                            <td>
                                                <a href="{{ route('reports.analytics.performance-metrics') }}" class="btn btn-sm btn-outline-success">View</a>
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
    </div>
</div>
@endsection
