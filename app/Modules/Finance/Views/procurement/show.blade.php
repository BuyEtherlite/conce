@extends('layouts.admin')

@section('page-title', 'Purchase Order Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🛒 Purchase Order #{{ $purchaseOrder->order_number }}</h4>
        <div>
            @if($purchaseOrder->status === 'pending')
                <form action="{{ route('finance.procurement.approve', $purchaseOrder->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" 
                            onclick="return confirm('Are you sure you want to approve this purchase order?')">
                        <i class="fas fa-check me-1"></i>Approve Order
                    </button>
                </form>
            @endif
            @if($purchaseOrder->status === 'approved')
                <a href="{{ route('finance.procurement.receive', $purchaseOrder->id) }}" class="btn btn-primary">
                    <i class="fas fa-inbox me-1"></i>Receive Items
                </a>
            @endif
            <a href="{{ route('finance.procurement.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Procurement
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Order Items</h6>
                </div>
                <div class="card-body">
                    @if($purchaseOrder->items && $purchaseOrder->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        @if($purchaseOrder->status !== 'pending')
                                            <th>Received</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->item->item_name ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>${{ number_format($item->total_price, 2) }}</td>
                                            @if($purchaseOrder->status !== 'pending')
                                                <td>{{ $item->quantity_received ?? 0 }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No items found for this purchase order.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Order Information</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Order Number:</dt>
                        <dd class="col-sm-7">{{ $purchaseOrder->order_number }}</dd>
                        
                        <dt class="col-sm-5">Supplier:</dt>
                        <dd class="col-sm-7">{{ $purchaseOrder->supplier->supplier_name ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Order Date:</dt>
                        <dd class="col-sm-7">{{ $purchaseOrder->order_date ? \Carbon\Carbon::parse($purchaseOrder->order_date)->format('M d, Y') : 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Delivery Date:</dt>
                        <dd class="col-sm-7">{{ $purchaseOrder->delivery_date ? \Carbon\Carbon::parse($purchaseOrder->delivery_date)->format('M d, Y') : 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{ $purchaseOrder->status === 'completed' ? 'success' : ($purchaseOrder->status === 'approved' ? 'primary' : ($purchaseOrder->status === 'pending' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($purchaseOrder->status ?? 'pending') }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-5">Created By:</dt>
                        <dd class="col-sm-7">{{ $purchaseOrder->creator->name ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="m-0">Order Summary</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Subtotal:</dt>
                        <dd class="col-sm-6">${{ number_format($purchaseOrder->subtotal, 2) }}</dd>
                        
                        <dt class="col-sm-6">Tax ({{ $purchaseOrder->tax_rate }}%):</dt>
                        <dd class="col-sm-6">${{ number_format($purchaseOrder->tax_amount, 2) }}</dd>
                        
                        <dt class="col-sm-6"><strong>Total:</strong></dt>
                        <dd class="col-sm-6"><strong>${{ number_format($purchaseOrder->total_amount, 2) }}</strong></dd>
                    </dl>
                </div>
            </div>

            @if($purchaseOrder->notes)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="m-0">Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $purchaseOrder->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
