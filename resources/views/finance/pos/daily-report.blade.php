@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">POS Daily Report</h1>
        <div class="btn-group">
            <a href="{{ route('finance.pos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to POS
            </a>
            <button class="btn btn-primary" onclick="printReport()">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Report Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('finance.pos.daily-report') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date">Report Date</label>
                                    <input type="date" class="form-control" id="date" name="date" 
                                           value="{{ $date }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terminal_id">Terminal</label>
                                    <select class="form-control" id="terminal_id" name="terminal_id">
                                        <option value="">All Terminals</option>
                                        @foreach(\App\Models\Finance\PosTerminal::where('is_active', true)->get() as $terminal)
                                            <option value="{{ $terminal->id }}" {{ $terminalId == $terminal->id ? 'selected' : '' }}>
                                                {{ $terminal->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Amount</h5>
                    <h3>${{ number_format($summary['total_amount'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions</h5>
                    <h3>{{ $summary['total_transactions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Average Transaction</h5>
                    <h3>${{ $summary['total_transactions'] > 0 ? number_format($summary['total_amount'] / $summary['total_transactions'], 2) : '0.00' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Terminals</h5>
                    <h3>{{ count($summary['terminals']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['payment_methods'] as $method => $data)
                                <tr>
                                    <td>{{ $method ?: 'Unknown' }}</td>
                                    <td class="text-right">{{ $data['count'] }}</td>
                                    <td class="text-right">${{ number_format($data['total'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No payment methods found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Terminal Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Terminal</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['terminals'] as $terminal => $data)
                                <tr>
                                    <td>{{ $terminal ?: 'Unknown' }}</td>
                                    <td class="text-right">{{ $data['count'] }}</td>
                                    <td class="text-right">${{ number_format($data['total'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No terminals found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hourly Breakdown -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hourly Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Hour</th>
                                    <th class="text-right">Transactions</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['hourly_breakdown'] as $hour => $data)
                                <tr>
                                    <td>{{ $hour }}</td>
                                    <td class="text-right">{{ $data['count'] }}</td>
                                    <td class="text-right">${{ number_format($data['total'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hourly data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detailed Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Terminal</th>
                                    <th>Collector</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                <tr>
                                    <td>{{ $collection->created_at->format('H:i:s') }}</td>
                                    <td>
                                        @if($collection->customerAccount && $collection->customerAccount->customer)
                                            {{ $collection->customerAccount->customer->first_name }} {{ $collection->customerAccount->customer->last_name }}
                                        @else
                                            Walk-in Customer
                                        @endif
                                    </td>
                                    <td class="text-right">${{ number_format($collection->amount_collected, 2) }}</td>
                                    <td>{{ $collection->payment_method ?: 'Cash' }}</td>
                                    <td>{{ $collection->posTerminal->name ?? 'Unknown' }}</td>
                                    <td>{{ $collection->collector->name ?? 'Unknown' }}</td>
                                    <td>
                                        <a href="{{ route('finance.pos.receipt', $collection) }}" 
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-receipt"></i> Receipt
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No transactions found for {{ $date }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($collections->count() > 0)
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">TOTAL</td>
                                    <td class="text-right">${{ number_format($summary['total_amount'], 2) }}</td>
                                    <td colspan="4">{{ $summary['total_transactions'] }} transactions</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    window.print();
}

// Initialize DataTable if available
$(document).ready(function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#transactionsTable').DataTable({
            "pageLength": 25,
            "order": [[ 0, "desc" ]], // Sort by time
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Actions column
            ]
        });
    }
});
</script>

<style>
@media print {
    .btn-group,
    .card:first-child,
    .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table th {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.table tfoot tr {
    border-top: 2px solid #dee2e6;
}
</style>
@endsection
