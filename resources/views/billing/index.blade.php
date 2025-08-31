@extends('layouts.admin')

@section('page-title', 'Municipal Billing & Invoicing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">💰 Municipal Billing & Invoicing</h1>
                <div>
                    <a href="{{ route('billing.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Bill
                    </a>
                    <a href="{{ route('billing.customers.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus"></i> Add Customer
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Total Bills</h5>
                                    <h3 class="mb-0">{{ number_format($stats['total_bills']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-invoice fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Pending</h5>
                                    <h3 class="mb-0">{{ number_format($stats['pending_bills']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Overdue</h5>
                                    <h3 class="mb-0">{{ number_format($stats['overdue_bills']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Total Revenue</h5>
                                    <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Outstanding</h5>
                                    <h3 class="mb-0">${{ number_format($stats['outstanding_amount'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-balance-scale fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Monthly Rev</h5>
                                    <h3 class="mb-0">${{ number_format($stats['monthly_revenue'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Bills -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Recent Bills</h5>
                            <a href="{{ route('billing.bills.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Bill #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_bills as $bill)
                                        <tr>
                                            <td>
                                                <a href="{{ route('billing.bills.show', $bill) }}" class="text-decoration-none">
                                                    {{ $bill->bill_number }}
                                                </a>
                                            </td>
                                            <td>{{ $bill->customerAccount->customer_name }}</td>
                                            <td>${{ number_format($bill->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'overdue' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($bill->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bill->bill_date->format('M d, Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No bills found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Bills -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Overdue Bills</h5>
                            <span class="badge badge-danger">{{ count($overdue_bills) }} overdue</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Bill #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Days Overdue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overdue_bills as $bill)
                                        <tr>
                                            <td>
                                                <a href="{{ route('billing.bills.show', $bill) }}" class="text-decoration-none">
                                                    {{ $bill->bill_number }}
                                                </a>
                                            </td>
                                            <td>{{ $bill->customerAccount->customer_name }}</td>
                                            <td>${{ number_format($bill->outstanding_amount, 2) }}</td>
                                            <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $bill->due_date->diffInDays(now()) }} days
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No overdue bills</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('billing.bills.index') }}" class="btn btn-outline-primary btn-block mb-2">
                                        <i class="fas fa-file-invoice"></i> Manage Bills
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('billing.payments.index') }}" class="btn btn-outline-success btn-block mb-2">
                                        <i class="fas fa-credit-card"></i> Record Payments
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('billing.customers.index') }}" class="btn btn-outline-info btn-block mb-2">
                                        <i class="fas fa-users"></i> Customer Accounts
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('billing.reports.index') }}" class="btn btn-outline-secondary btn-block mb-2">
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
@endsection