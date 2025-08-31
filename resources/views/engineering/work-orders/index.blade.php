@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Work Orders</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.work-orders.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Work Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($workOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Work Order #</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrders as $workOrder)
                                <tr>
                                    <td><strong>{{ $workOrder->work_order_number ?? 'WO-' . str_pad($workOrder->id ?? 1, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                    <td>{{ $workOrder->title ?? 'Sample Work Order' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($workOrder->type ?? 'maintenance') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($workOrder->priority ?? 'medium') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($workOrder->status ?? 'pending') }}</span>
                                    </td>
                                    <td>{{ $workOrder->assigned_to ?? 'Engineering Team' }}</td>
                                    <td>{{ $workOrder->required_date ? date('M d, Y', strtotime($workOrder->required_date)) : 'Not set' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.work-orders.show', $workOrder->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.work-orders.edit', $workOrder->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-check display-4 text-muted"></i>
                        <h5 class="mt-3">No Work Orders Found</h5>
                        <p class="text-muted">Create your first work order to get started.</p>
                        <a href="{{ route('engineering.work-orders.create') }}" class="btn btn-primary">Create Work Order</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
