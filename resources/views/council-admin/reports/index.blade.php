@extends('layouts.council-admin')

@section('title', 'Reports - Council Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">📊 System Reports</h2>
                    <p class="text-muted">Generate and view comprehensive system reports</p>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="row">
                <!-- Financial Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">💰 Financial Reports</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Generate comprehensive financial reports including revenue, expenditure, and budget analysis.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('finance.reports.index') }}" class="btn btn-primary btn-sm">Revenue Reports</a>
                                <a href="{{ route('finance.budgets.index') }}" class="btn btn-outline-primary btn-sm">Budget Reports</a>
                                <a href="{{ route('finance.reports.balance-sheet') }}" class="btn btn-outline-primary btn-sm">Balance Sheet</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activity Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">👥 User Activity</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Monitor user activities, login patterns, and system usage statistics.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('council-admin.reports.audit-logs') }}" class="btn btn-info btn-sm">Audit Logs</a>
                                <a href="#" class="btn btn-outline-info btn-sm">Login Reports</a>
                                <a href="#" class="btn btn-outline-info btn-sm">User Statistics</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Usage Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">📈 Module Usage</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Track module usage, performance metrics, and system efficiency.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('council-admin.core-modules.index') }}" class="btn btn-success btn-sm">Module Status</a>
                                <a href="#" class="btn btn-outline-success btn-sm">Usage Analytics</a>
                                <a href="#" class="btn btn-outline-success btn-sm">Performance</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Requests Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-warning">
                        <div class="card-header bg-warning text-white">
                            <h5 class="card-title mb-0">🎫 Service Requests</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Analyze service request patterns, response times, and resolution statistics.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('public-services.service-requests.index') }}" class="btn btn-warning btn-sm">Request Overview</a>
                                <a href="#" class="btn btn-outline-warning btn-sm">Response Times</a>
                                <a href="#" class="btn btn-outline-warning btn-sm">Resolution Stats</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Housing Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">🏠 Housing Reports</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Generate housing allocation, waiting list, and property management reports.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('housing.applications.index') }}" class="btn btn-secondary btn-sm">Applications</a>
                                <a href="{{ route('housing.waiting-list.index') }}" class="btn btn-outline-secondary btn-sm">Waiting List</a>
                                <a href="{{ route('housing.allocations.index') }}" class="btn btn-outline-secondary btn-sm">Allocations</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health Reports -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">🔧 System Health</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Monitor system performance, database health, and error tracking.</p>
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-danger btn-sm">System Status</a>
                                <a href="#" class="btn btn-outline-danger btn-sm">Error Logs</a>
                                <a href="#" class="btn btn-outline-danger btn-sm">Performance</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📊 Quick Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-primary">{{ $stats['total_users'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Total Users</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-success">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                                        <p class="text-muted mb-0">Total Revenue</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-info">{{ $stats['pending_requests'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Pending Requests</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-warning">{{ $stats['active_modules'] ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Active Modules</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
