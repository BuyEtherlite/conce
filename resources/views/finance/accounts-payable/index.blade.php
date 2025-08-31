@extends('layouts.admin')

@section('title', 'Accounts Payable')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>💳 Accounts Payable</h1>
                <div>
                    <a href="{{ route('finance.accounts-payable.suppliers.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> New Supplier
                    </a>
                    <a href="{{ route('finance.accounts-payable.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Bill
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Suppliers</div>
                                    <div class="h5 mb-0 font-weight-bold">{{ $stats['total_suppliers'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Outstanding Bills</div>
                                    <div class="h5 mb-0 font-weight-bold">{{ $stats['outstanding_bills'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Payable</div>
                                    <div class="h5 mb-0 font-weight-bold">${{ number_format($stats['total_payable'] ?? 0, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Overdue Bills</div>
                                    <div class="h5 mb-0 font-weight-bold">{{ $stats['overdue_bills'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bills -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Bills</h6>
                            <a href="{{ route('finance.accounts-payable.bills.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            @if(isset($recentBills) && $recentBills->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Bill #</th>
                                                <th>Supplier</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentBills as $bill)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('finance.accounts-payable.bills.show', $bill) }}" class="text-decoration-none">
                                                        {{ $bill->bill_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $bill->supplier->supplier_name ?? 'N/A' }}</td>
                                                <td>${{ number_format($bill->bill_amount, 2) }}</td>
                                                <td>{{ $bill->due_date ? $bill->due_date->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'overdue' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($bill->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No recent bills found.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Overdue Payments -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Overdue Payments</h6>
                        </div>
                        <div class="card-body">
                            @if(isset($overduePayments) && $overduePayments->count() > 0)
                                @foreach($overduePayments as $payment)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-danger">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">{{ $payment->supplier->supplier_name ?? 'Unknown' }}</div>
                                        <div class="font-weight-bold">${{ number_format($payment->balance_due, 2) }}</div>
                                        <div class="text-xs text-danger">Due: {{ $payment->due_date ? $payment->due_date->format('M d, Y') : 'N/A' }}</div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-4">No overdue payments.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('finance.accounts-payable.suppliers.index') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-users"></i> Manage Suppliers
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('finance.accounts-payable.bills.index') }}" class="btn btn-outline-info btn-block">
                                        <i class="fas fa-file-invoice"></i> View All Bills
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('finance.accounts-payable.payments.index') }}" class="btn btn-outline-success btn-block">
                                        <i class="fas fa-credit-card"></i> Payment History
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('finance.accounts-payable.reports.index') }}" class="btn btn-outline-secondary btn-block">
                                        <i class="fas fa-chart-bar"></i> Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
