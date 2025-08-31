@extends('layouts.council-admin')

@section('title', 'System Audit Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">System Audit Logs</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Audit Logs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filter Audit Logs</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="user_filter" class="form-label">User</label>
                                <select class="form-select" id="user_filter" name="user_filter">
                                    <option value="">All Users</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="action_filter" class="form-label">Action Type</label>
                                <select class="form-select" id="action_filter" name="action_filter">
                                    <option value="">All Actions</option>
                                    <option value="login">Login</option>
                                    <option value="logout">Logout</option>
                                    <option value="create">Create</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="filterLogs()">
                                <i class="mdi mdi-filter"></i> Apply Filter
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                <i class="mdi mdi-refresh"></i> Clear Filters
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportLogs()">
                                <i class="mdi mdi-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="audit-logs-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date/Time</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample audit log entries -->
                                <tr>
                                    <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ auth()->user()->name ?? 'System Admin' }}</td>
                                    <td><span class="badge bg-success">Login</span></td>
                                    <td>Authentication</td>
                                    <td>User logged into council admin panel</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subMinutes(15)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ auth()->user()->name ?? 'System Admin' }}</td>
                                    <td><span class="badge bg-info">View</span></td>
                                    <td>Users</td>
                                    <td>Accessed user management page</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subMinutes(30)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ auth()->user()->name ?? 'System Admin' }}</td>
                                    <td><span class="badge bg-warning">Update</span></td>
                                    <td>Settings</td>
                                    <td>Modified system configuration</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subHours(1)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ auth()->user()->name ?? 'System Admin' }}</td>
                                    <td><span class="badge bg-primary">Create</span></td>
                                    <td>Users</td>
                                    <td>Created new user account</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subHours(2)->format('Y-m-d H:i:s') }}</td>
                                    <td>Unknown User</td>
                                    <td><span class="badge bg-danger">Login Failed</span></td>
                                    <td>Authentication</td>
                                    <td>Failed login attempt with invalid credentials</td>
                                    <td>192.168.1.100</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">Showing 1 to 5 of 125 entries</small>
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Activity Summary -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Activity Summary</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Action Type</th>
                                    <th>Count (Last 24h)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Successful Logins</td>
                                    <td><span class="badge bg-success">24</span></td>
                                </tr>
                                <tr>
                                    <td>Failed Login Attempts</td>
                                    <td><span class="badge bg-danger">3</span></td>
                                </tr>
                                <tr>
                                    <td>Data Modifications</td>
                                    <td><span class="badge bg-warning">12</span></td>
                                </tr>
                                <tr>
                                    <td>Report Generations</td>
                                    <td><span class="badge bg-info">8</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Security Alerts</h5>
                    <div class="alert alert-warning" role="alert">
                        <i class="mdi mdi-alert-triangle"></i>
                        <strong>Note:</strong> 3 failed login attempts detected in the last hour from IP 192.168.1.100
                    </div>
                    <div class="alert alert-info" role="alert">
                        <i class="mdi mdi-information"></i>
                        <strong>Info:</strong> System backup completed successfully at {{ now()->subHours(6)->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterLogs() {
    // Implementation for filtering logs
    console.log('Filtering logs...');
    // In a real implementation, this would make an AJAX request with the filter parameters
}

function clearFilters() {
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('user_filter').value = '';
    document.getElementById('action_filter').value = '';
    // Reload the table with no filters
}

function exportLogs() {
    // Implementation for exporting logs to CSV
    console.log('Exporting logs...');
    // In a real implementation, this would trigger a download of the filtered logs
}
</script>
@endsection
