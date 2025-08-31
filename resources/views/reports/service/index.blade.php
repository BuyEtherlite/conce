@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Service Reports</h1>
        <div class="form-inline">
            <form method="GET" action="{{ route('reports.service') }}" class="form-inline">
                <input type="date" name="start_date" class="form-control mr-2" value="{{ $startDate }}" required>
                <input type="date" name="end_date" class="form-control mr-2" value="{{ $endDate }}" required>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Service Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $requestsByStatus->sum('count') }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requestsByStatus->where('status', 'submitted')->first()->count ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requestsByStatus->where('status', 'in_progress')->first()->count ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
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
                                Average Resolution</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgResolutionTime, 1) }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Requests by Status -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Requests by Status</h6>
                </div>
                <div class="card-body">
                    @if($requestsByStatus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = $requestsByStatus->sum('count'); @endphp
                                    @foreach($requestsByStatus as $status)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $status->status)) }}</td>
                                        <td>{{ $status->count }}</td>
                                        <td>{{ $total > 0 ? number_format(($status->count / $total) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No data available for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Requests by Priority -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Requests by Priority</h6>
                </div>
                <div class="card-body">
                    @if($requestsByPriority->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Priority</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requestsByPriority as $priority)
                                    <tr>
                                        <td>
                                            <span class="badge badge-{{ $priority->priority === 'high' || $priority->priority === 'urgent' || $priority->priority === 'emergency' ? 'danger' : ($priority->priority === 'medium' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($priority->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $priority->count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Requests by Department -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Requests by Department</h6>
                </div>
                <div class="card-body">
                    @if($requestsByDepartment->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Total Requests</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = $requestsByDepartment->sum('count'); @endphp
                                    @foreach($requestsByDepartment as $dept)
                                    <tr>
                                        <td>{{ $dept->department_name }}</td>
                                        <td>{{ $dept->count }}</td>
                                        <td>{{ $total > 0 ? number_format(($dept->count / $total) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No data available for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
