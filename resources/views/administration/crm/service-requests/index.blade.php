@extends('layouts.app')

@section('page-title', 'Service Requests')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>📋 Service Requests</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('administration.index') }}">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.crm.index') }}">CRM</a></li>
                    <li class="breadcrumb-item active">Service Requests</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('administration.service-requests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create Request
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Service Requests</h5>
        </div>
        <div class="card-body">
            @if($serviceRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Request #</th>
                                <th>Customer</th>
                                <th>Service Type</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $request)
                            <tr>
                                <td>{{ $request->request_number }}</td>
                                <td>{{ $request->customer->full_name ?? 'N/A' }}</td>
                                <td>{{ $request->service_type }}</td>
                                <td>{{ $request->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->priority === 'urgent' ? 'danger' : ($request->priority === 'high' ? 'warning' : 'info') }}">
                                        {{ ucfirst($request->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->assignedUser->name ?? 'Unassigned' }}</td>
                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('administration.service-requests.show', $request) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
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
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5>No Service Requests Found</h5>
                    <p class="text-muted">Start by creating your first service request.</p>
                    <a href="{{ route('administration.service-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Request
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
