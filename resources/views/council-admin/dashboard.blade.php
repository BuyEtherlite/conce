
@extends('layouts.council-admin')

@section('title', 'Council Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Council Admin Dashboard
                </h1>
                <div class="btn-group">
                    <a href="{{ route('council-admin.dashboard') }}?refresh=1" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </a>
                    <form method="POST" action="{{ route('council-admin.dashboard.clear-cache') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-trash"></i> Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_users'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> {{ $stats['recent_users'] ?? 0 }} new this month
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Modules -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Modules</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['enabled_modules'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-puzzle-piece fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            {{ $stats['total_modules'] ?? 0 }} total modules
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Requests -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['pending_service_requests'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            {{ number_format($stats['total_service_requests'] ?? 0) }} total requests
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            ${{ number_format($stats['yearly_revenue'] ?? 0, 2) }} this year
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- System Health -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat me-2"></i>System Health
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <i class="fas fa-database fa-2x 
                                    @if($systemHealth['database_status'] === 'healthy') text-success
                                    @elseif($systemHealth['database_status'] === 'warning') text-warning
                                    @else text-danger @endif"></i>
                                <h6 class="mt-2">Database</h6>
                                <span class="badge 
                                    @if($systemHealth['database_status'] === 'healthy') bg-success
                                    @elseif($systemHealth['database_status'] === 'warning') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($systemHealth['database_status']) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <i class="fas fa-memory fa-2x 
                                    @if($systemHealth['cache_status'] === 'healthy') text-success
                                    @elseif($systemHealth['cache_status'] === 'warning') text-warning
                                    @else text-danger @endif"></i>
                                <h6 class="mt-2">Cache</h6>
                                <span class="badge 
                                    @if($systemHealth['cache_status'] === 'healthy') bg-success
                                    @elseif($systemHealth['cache_status'] === 'warning') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($systemHealth['cache_status']) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <i class="fas fa-hdd fa-2x 
                                    @if($systemHealth['storage_status'] === 'healthy') text-success
                                    @elseif($systemHealth['storage_status'] === 'warning') text-warning
                                    @else text-danger @endif"></i>
                                <h6 class="mt-2">Storage</h6>
                                <span class="badge 
                                    @if($systemHealth['storage_status'] === 'healthy') bg-success
                                    @elseif($systemHealth['storage_status'] === 'warning') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($systemHealth['storage_status']) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <i class="fas fa-tasks fa-2x 
                                    @if($systemHealth['queue_status'] === 'healthy') text-success
                                    @elseif($systemHealth['queue_status'] === 'warning') text-warning
                                    @else text-danger @endif"></i>
                                <h6 class="mt-2">Queues</h6>
                                <span class="badge 
                                    @if($systemHealth['queue_status'] === 'healthy') bg-success
                                    @elseif($systemHealth['queue_status'] === 'warning') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($systemHealth['queue_status']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-microchip me-1"></i>
                                Memory Usage: {{ $systemHealth['memory_usage'] ?? 'Unknown' }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-hdd me-1"></i>
                                Disk Space: {{ $systemHealth['disk_usage'] ?? 'Unknown' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Performance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-success">{{ $performanceMetrics['uptime'] ?? '99.9%' }}</h4>
                        <small class="text-muted">System Uptime</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Response Time</span>
                            <span class="small">{{ $performanceMetrics['avg_response_time'] ?? '0ms' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Error Rate</span>
                            <span class="small">{{ $performanceMetrics['error_rate'] ?? '0%' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Active Sessions</span>
                            <span class="small">{{ $performanceMetrics['active_sessions'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Status and Recent Activity -->
    <div class="row">
        <!-- Module Status -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-puzzle-piece me-2"></i>Module Status
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($stats['module_status']) && !empty($stats['module_status']))
                        <div class="row">
                            @foreach($stats['module_status'] as $key => $module)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if(is_array($module) && isset($module['is_active']))
                                                <i class="fas fa-circle {{ $module['is_active'] ? 'text-success' : 'text-danger' }}"></i>
                                            @else
                                                <i class="fas fa-circle {{ $module ? 'text-success' : 'text-danger' }}"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="small font-weight-bold">
                                                @if(is_array($module) && isset($module['name']))
                                                    {{ $module['name'] }}
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-muted">
                                                @if(is_array($module) && isset($module['version']))
                                                    v{{ $module['version'] }}
                                                @else
                                                    v1.0.0
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No modules configured</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivity['recent_service_requests']) && $recentActivity['recent_service_requests']->count() > 0)
                        <h6 class="text-sm font-weight-bold mb-2">Recent Service Requests</h6>
                        @foreach($recentActivity['recent_service_requests']->take(3) as $request)
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <span class="badge bg-{{ 
                                        $request->status === 'pending' ? 'warning' : 
                                        ($request->status === 'completed' ? 'success' : 'info') 
                                    }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small">{{ $request->title ?? 'Service Request' }}</div>
                                    <div class="text-xs text-muted">
                                        {{ $request->customer->name ?? 'Unknown Customer' }} - 
                                        {{ $request->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(isset($recentActivity['recent_users']) && $recentActivity['recent_users']->count() > 0)
                        <hr>
                        <h6 class="text-sm font-weight-bold mb-2">Recent Users</h6>
                        @foreach($recentActivity['recent_users']->take(3) as $user)
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <i class="fas fa-user-circle text-primary"></i>
                                </div>
                                <div>
                                    <div class="small">{{ $user->name }}</div>
                                    <div class="text-xs text-muted">
                                        {{ $user->email }} - {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if((!isset($recentActivity['recent_service_requests']) || $recentActivity['recent_service_requests']->count() === 0) && 
                        (!isset($recentActivity['recent_users']) || $recentActivity['recent_users']->count() === 0))
                        <p class="text-muted text-center">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <!-- User Statistics -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>User Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="userChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="small d-flex justify-content-between">
                            <span>Active Users:</span>
                            <span>{{ number_format($stats['active_users'] ?? 0) }}</span>
                        </div>
                        <div class="small d-flex justify-content-between">
                            <span>Inactive Users:</span>
                            <span>{{ number_format($stats['inactive_users'] ?? 0) }}</span>
                        </div>
                        <div class="small d-flex justify-content-between">
                            <span>Admin Users:</span>
                            <span>{{ number_format($stats['admin_users'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Request Statistics -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Service Requests
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Pending:</span>
                            <span class="small text-warning">{{ number_format($stats['pending_service_requests'] ?? 0) }}</span>
                        </div>
                        <div class="progress progress-sm mb-2">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['total_service_requests'] > 0 ? ($stats['pending_service_requests'] / $stats['total_service_requests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">In Progress:</span>
                            <span class="small text-info">{{ number_format($stats['in_progress_requests'] ?? 0) }}</span>
                        </div>
                        <div class="progress progress-sm mb-2">
                            <div class="progress-bar bg-info" style="width: {{ $stats['total_service_requests'] > 0 ? ($stats['in_progress_requests'] / $stats['total_service_requests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="small">Completed:</span>
                            <span class="small text-success">{{ number_format($stats['completed_requests'] ?? 0) }}</span>
                        </div>
                        <div class="progress progress-sm mb-2">
                            <div class="progress-bar bg-success" style="width: {{ $stats['total_service_requests'] > 0 ? ($stats['completed_requests'] / $stats['total_service_requests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('council-admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="{{ route('council-admin.modules.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-puzzle-piece me-2"></i>Configure Modules
                        </a>
                        <a href="{{ route('council-admin.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                        <a href="{{ route('council-admin.reports.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-chart-bar me-2"></i>View Reports
                        </a>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// User statistics chart
const userCtx = document.getElementById('userChart');
if (userCtx) {
    const userChart = new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [{{ $stats['active_users'] ?? 0 }}, {{ $stats['inactive_users'] ?? 0 }}],
                backgroundColor: ['#1cc88a', '#e74a3b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
}

// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

function refreshDashboard() {
    window.location.href = "{{ route('council-admin.dashboard') }}?refresh=1";
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection

@section('styles')
<style>
.card {
    border-radius: 0.5rem;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.progress-sm {
    height: 0.5rem;
}

.text-xs {
    font-size: 0.75rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}
</style>
@endsection
