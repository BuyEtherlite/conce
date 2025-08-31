@extends('layouts.app')

@section('title', 'Performance Metrics Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Performance Metrics Report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.analytics.index') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">Performance Metrics</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($metrics['user_activity']) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completion Rate</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['completion_rate'] }}%</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Avg Response Time</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['response_time'] }}s</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Performance Overview</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Key performance indicators showing system efficiency and user engagement metrics.</p>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Metric</th>
                                            <th>Current Value</th>
                                            <th>Target</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>User Activity</td>
                                            <td>{{ $metrics['user_activity'] }} active users</td>
                                            <td>100+ users</td>
                                            <td>
                                                @if($metrics['user_activity'] >= 100)
                                                    <span class="badge badge-success">On Target</span>
                                                @else
                                                    <span class="badge badge-warning">Below Target</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Completion Rate</td>
                                            <td>{{ $metrics['completion_rate'] }}%</td>
                                            <td>90%</td>
                                            <td>
                                                @if($metrics['completion_rate'] >= 90)
                                                    <span class="badge badge-success">Excellent</span>
                                                @elseif($metrics['completion_rate'] >= 80)
                                                    <span class="badge badge-info">Good</span>
                                                @else
                                                    <span class="badge badge-warning">Needs Improvement</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Response Time</td>
                                            <td>{{ $metrics['response_time'] }} seconds</td>
                                            <td>&lt; 3.0s</td>
                                            <td>
                                                @if($metrics['response_time'] < 3.0)
                                                    <span class="badge badge-success">Optimal</span>
                                                @else
                                                    <span class="badge badge-warning">Needs Optimization</span>
                                                @endif
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
