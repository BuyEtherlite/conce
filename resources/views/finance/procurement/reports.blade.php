@extends('layouts.admin')

@section('page-title', 'Procurement Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📊 Procurement Reports</h4>
        <a href="{{ route('finance.procurement.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Procurement
        </a>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="m-0">Report Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.procurement.reports') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter Reports
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shopping-cart fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title">Total Orders</h6>
                            <h4 class="mb-0">{{ $purchaseOrders->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dollar-sign fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title">Total Value</h6>
                            <h4 class="mb-0">${{ number_format($totalValue, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-building fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title">Suppliers</h6>
                            <h4 class="mb-0">{{ $supplierSummary->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title">Pending Orders</h6>
                            <h4 class="mb-0">{{ $statusSummary['pending'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Purchase Orders List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Purchase Orders</h6>
                </div>
                <div class="card-body">
                    @if($purchaseOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Supplier</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->supplier->supplier_name ?? 'N/A' }}</td>
                                            <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y') : 'N/A' }}</td>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'approved' ? 'primary' : ($order->status === 'pending' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($order->status ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No purchase orders found for the selected date range.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Charts -->
        <div class="col-md-4">
            <!-- Status Summary -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="m-0">Order Status Summary</h6>
                </div>
                <div class="card-body">
                    @foreach($statusSummary as $status => $count)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ ucfirst($status) }}:</span>
                            <span class="badge bg-{{ $status === 'completed' ? 'success' : ($status === 'approved' ? 'primary' : ($status === 'pending' ? 'warning' : 'secondary')) }}">
                                {{ $count }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Supplier Summary -->
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Top Suppliers</h6>
                </div>
                <div class="card-body">
                    @foreach($supplierSummary->take(5) as $supplierName => $data)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <small>{{ $supplierName }}</small>
                                <small>${{ number_format($data['total_value'], 0) }}</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $totalValue > 0 ? ($data['total_value'] / $totalValue) * 100 : 0 }}%">
                                </div>
                            </div>
                            <small class="text-muted">{{ $data['count'] }} orders</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
