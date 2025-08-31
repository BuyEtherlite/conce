@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Maintenance Requests</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.maintenance.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($maintenanceRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Asset</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Requested Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenanceRequests as $request)
                                <tr>
                                    <td><strong>{{ $request->request_number ?? 'MR-001' }}</strong></td>
                                    <td>{{ $request->asset_name ?? 'Sample Asset' }}</td>
                                    <td>{{ ucfirst($request->type ?? 'preventive') }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($request->priority ?? 'medium') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($request->status ?? 'pending') }}</span>
                                    </td>
                                    <td>{{ $request->created_at ? date('M d, Y', strtotime($request->created_at)) : date('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.maintenance.show', $request->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-wrench display-4 text-muted"></i>
                        <h5 class="mt-3">No Maintenance Requests Found</h5>
                        <p class="text-muted">Create your first maintenance request to get started.</p>
                        <a href="{{ route('engineering.maintenance.create') }}" class="btn btn-primary">Create Request</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
