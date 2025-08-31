@extends('layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">Service Requests</h1>
                    <p class="page-subtitle">Manage and track all service requests</p>
                </div>
                <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('service-requests.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-control">
                                    <option value="">All Priorities</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="emergency" {{ request('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="service_type_id" class="form-label">Service Type</label>
                                <select name="service_type_id" id="service_type_id" class="form-control">
                                    <option value="">All Service Types</option>
                                    @foreach($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}" {{ request('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                            {{ $serviceType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Requests</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($stats['total_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($stats['pending_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">In Progress</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($stats['in_progress_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Completed</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($stats['completed_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Service Requests</h5>
                </div>
                <div class="card-body">
                    @if($serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Customer</th>
                                        <th>Title</th>
                                        <th>Service Type</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequests as $request)
                                    <tr>
                                        <td>
                                            {{ $request->request_number }}
                                            @if($request->is_emergency)
                                                <span class="badge bg-danger ms-1">Emergency</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->customer->full_name ?? 'N/A' }}</td>
                                        <td>{{ $request->title }}</td>
                                        <td>{{ $request->serviceType->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $request->priority == 'low' ? 'secondary' : 
                                                ($request->priority == 'medium' ? 'info' : 
                                                ($request->priority == 'high' ? 'warning' : 
                                                ($request->priority == 'urgent' ? 'orange' : 'danger'))) 
                                            }}">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $request->status == 'completed' ? 'success' :
                                                ($request->status == 'in_progress' ? 'primary' :
                                                ($request->status == 'assigned' ? 'info' :
                                                ($request->status == 'on_hold' ? 'secondary' :
                                                ($request->status == 'cancelled' ? 'danger' : 'warning'))))
                                            }}">
                                                {{ ucwords(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>{{ $request->assignedTeam->name ?? 'Unassigned' }}</td>
                                        <td>
                                            <a href="{{ route('service-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('service-requests.edit', $request) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $serviceRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No service requests found.</p>
                            <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                                Create First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
