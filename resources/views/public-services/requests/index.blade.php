@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt fa-fw"></i> Service Requests
        </h1>
        <div class="d-none d-lg-inline-block">
            <a href="{{ route('public-services.requests.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> New Request
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('public-services.requests') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="priority" class="form-control">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="emergency" {{ request('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('public-services.requests') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Requests</h6>
        </div>
        <div class="card-body">
            @if($serviceRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Request #</th>
                                <th>Customer</th>
                                <th>Service Type</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Requested Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $request)
                            <tr>
                                <td>
                                    {{ $request->request_number }}
                                    @if($request->is_emergency)
                                        <span class="badge badge-danger ml-1">Emergency</span>
                                    @endif
                                </td>
                                <td>{{ $request->customer->full_name ?? 'N/A' }}</td>
                                <td>{{ $request->serviceType->name ?? 'N/A' }}</td>
                                <td>{{ $request->title }}</td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $request->priority == 'low' ? 'secondary' : 
                                        ($request->priority == 'medium' ? 'info' : 
                                        ($request->priority == 'high' ? 'warning' : 
                                        ($request->priority == 'urgent' ? 'orange' : 'danger'))) 
                                    }}">
                                        {{ ucfirst($request->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $request->status == 'completed' ? 'success' : 
                                        ($request->status == 'in_progress' ? 'primary' : 
                                        ($request->status == 'submitted' ? 'warning' : 'secondary')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </td>
                                <td>{{ $request->requested_date->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('public-services.requests.show', $request) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $serviceRequests->links() }}
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">No service requests found.</p>
                    <a href="{{ route('public-services.requests.create') }}" class="btn btn-primary">
                        Create First Request
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
