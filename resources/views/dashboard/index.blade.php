@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="h3 mb-0">Welcome back, {{ $user->name ?? 'User' }}!</h1>
                            <p class="mb-0 opacity-75">Council Management System Dashboard</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Modules</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_modules'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_requests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Revenue</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Customers</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Departments</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_departments'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Completed Requests</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_requests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today's Revenue</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['today_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('crm.customers.create') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-user-plus"></i> Add Customer
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('service-requests.create') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-plus-circle"></i> New Service Request
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.pos.index') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-cash-register"></i> POS Terminal
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-chart-bar"></i> View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    @if($availableModules && $availableModules->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Available Modules</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availableModules as $module)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ $module->display_name ?? $module->name ?? 'Module' }}
                                            </div>
                                            <div class="text-gray-800 small mb-2">
                                                {{ $module->description ?? 'No description available' }}
                                            </div>
                                            @if(isset($module->is_active) && $module->is_active)
                                                <a href="{{ route($module->route ?? '#') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-{{ $module->icon ?? 'cube' }}"></i> Access Module
                                                </a>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-{{ $module->icon ?? 'cube' }} fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    @if(isset($recentRequests) && $recentRequests->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->title ?? 'Service Request #' . $request->id }}</td>
                                    <td>{{ $request->customer->name ?? 'Unknown Customer' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($request->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>{{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->diffForHumans() : 'Unknown' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!isset($recentRequests) || $recentRequests->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No recent activity</h5>
                    <p class="text-gray-500">Start using the system to see activity here.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.text-xs {
    font-size: 0.75rem;
}

.card {
    border-radius: 0.35rem;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem 0 rgba(58, 59, 69, 0.2) !important;
}
</style>
@endsection
