@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Debtors Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to AR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Customers</h6>
                    <h4 class="text-primary">{{ $customers->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Outstanding</h6>
                    <h4 class="text-warning">${{ number_format($customers->sum('balance'), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Customers with Balance</h6>
                    <h4 class="text-info">{{ $customers->where('balance', '>', 0)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Invoiced</h6>
                    <h4 class="text-success">${{ number_format($customers->sum('total_invoiced'), 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Debtors Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Debtors List</h4>
                    <div class="card-header-actions">
                        <a href="{{ route('finance.debtors.aging-report') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-chart-bar"></i> Aging Report
                        </a>
                        <a href="{{ route('finance.debtors.statements') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-alt"></i> Statements
                        </a>
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
                                    <th>Total Invoiced</th>
                                    <th>Total Paid</th>
                                    <th>Outstanding Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr class="{{ $customer->balance > 0 ? 'table-warning' : '' }}">
                                    <td><strong>{{ $customer->customer_code ?? 'N/A' }}</strong></td>
                                    <td>{{ $customer->company_name ?? $customer->first_name . ' ' . $customer->last_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>${{ number_format($customer->total_invoiced ?? 0, 2) }}</td>
                                    <td>${{ number_format($customer->total_paid ?? 0, 2) }}</td>
                                    <td>
                                        @if($customer->balance > 0)
                                            <span class="badge badge-warning">${{ number_format($customer->balance, 2) }}</span>
                                        @elseif($customer->balance < 0)
                                            <span class="badge badge-success">${{ number_format(abs($customer->balance), 2) }} Credit</span>
                                        @else
                                            <span class="badge badge-secondary">$0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('finance.debtors.show', $customer->id) }}" 
                                               class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('finance.debtors.customer-statement', $customer->id) }}" 
                                               class="btn btn-sm btn-outline-info">Statement</a>
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
</div>
@endsection
