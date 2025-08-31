@extends('layouts.admin')

@section('page-title', 'Procurement Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🛒 Procurement Management</h4>
        <div>
            <a href="{{ route('finance.procurement.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Purchase Order
            </a>
            <a href="{{ route('finance.procurement.suppliers') }}" class="btn btn-outline-secondary">
                <i class="fas fa-truck me-1"></i>Suppliers
            </a>
            <a href="{{ route('finance.procurement.reports') }}" class="btn btn-outline-info">
                <i class="fas fa-chart-bar me-1"></i>Reports
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="m-0">Purchase Orders</h6>
        </div>
        <div class="card-body">
            @if($purchaseOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Supplier</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->supplier->supplier_name ?? 'N/A' }}</td>
                                    <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'approved' ? 'primary' : ($order->status === 'pending' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('finance.procurement.show', $order->id) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($order->status === 'pending')
                                                <form action="{{ route('finance.procurement.approve', $order->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" 
                                                            onclick="return confirm('Are you sure you want to approve this purchase order?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($order->status === 'approved')
                                                <a href="{{ route('finance.procurement.receive', $order->id) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-inbox"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $purchaseOrders->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5>No Purchase Orders Found</h5>
                    <p class="text-muted">Start by creating your first purchase order.</p>
                    <a href="{{ route('finance.procurement.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>New Purchase Order
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
