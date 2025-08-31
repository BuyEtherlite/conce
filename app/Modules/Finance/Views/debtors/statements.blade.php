@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customer Statements</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.debtors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Debtors
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Statements -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Statements</h4>
                    <div class="card-header-actions">
                        <button class="btn btn-primary btn-sm" onclick="printStatements()">
                            <i class="fas fa-print"></i> Print All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Invoices</th>
                                    <th>Total Receipts</th>
                                    <th>Outstanding Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td><strong>{{ $customer->customer_code ?? 'N/A' }}</strong></td>
                                    <td>{{ $customer->company_name ?? $customer->first_name . ' ' . $customer->last_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->invoices->count() }}</td>
                                    <td>{{ $customer->receipts->count() }}</td>
                                    <td>
                                        @php
                                            $totalInvoiced = $customer->invoices->sum('total_amount');
                                            $totalPaid = $customer->receipts->sum('amount');
                                            $balance = $totalInvoiced - $totalPaid;
                                        @endphp
                                        @if($balance > 0)
                                            <span class="badge badge-warning">${{ number_format($balance, 2) }}</span>
                                        @elseif($balance < 0)
                                            <span class="badge badge-success">${{ number_format(abs($balance), 2) }} Credit</span>
                                        @else
                                            <span class="badge badge-secondary">$0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('finance.debtors.customer-statement', $customer->id) }}" 
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-file-alt"></i> View Statement
                                            </a>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    onclick="emailStatement({{ $customer->id }})">
                                                <i class="fas fa-envelope"></i> Email
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No customers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Generate Statement for Date Range</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('finance.debtors.statements') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="{{ route('finance.debtors.statements') }}" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printStatements() {
    window.print();
}

function emailStatement(customerId) {
    // You can implement email functionality here
    alert('Email statement functionality to be implemented for customer ID: ' + customerId);
}
</script>

<style>
@media print {
    .page-title-right,
    .card-header-actions,
    .btn-group {
        display: none !important;
    }
}
</style>
@endsection
