@extends('layouts.app')

@section('page-title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-user me-2"></i>Customer: {{ $customer->customer_name }}</h4>
        <div>
            <a href="{{ route('finance.accounts-receivable.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit Customer
            </a>
            <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Customers
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Account Number:</strong> {{ $customer->account_number }}</p>
                            <p><strong>Customer Name:</strong> {{ $customer->customer_name }}</p>
                            <p><strong>Account Type:</strong> {{ ucfirst($customer->account_type) }}</p>
                            <p><strong>Contact Person:</strong> {{ $customer->contact_person ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Physical Address:</strong> {{ $customer->physical_address }}</p>
                            <p><strong>Postal Address:</strong> {{ $customer->postal_address }}</p>
                            <p><strong>Tax Number:</strong> {{ $customer->id_number ?? 'N/A' }}</p>
                            <p><strong>Credit Limit:</strong> ${{ number_format($customer->credit_limit, 2) }}</p>
                            <p><strong>Current Balance:</strong> ${{ number_format($customer->current_balance, 2) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Account Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h5 class="text-primary">${{ number_format($customer->current_balance, 2) }}</h5>
                            <small class="text-muted">Current Balance</small>
                        </div>
                        <div class="mb-3">
                            <h6>{{ $customer->bills->where('status', '!=', 'paid')->count() }}</h6>
                            <small class="text-muted">Outstanding Bills</small>
                        </div>
                        <div class="mb-3">
                            <h6>${{ number_format($customer->payments->sum('amount'), 2) }}</h6>
                            <small class="text-muted">Total Payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bills -->
    <div class="card shadow mt-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Recent Bills</h6>
        </div>
        <div class="card-body">
            @if($customer->bills->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bill Number</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Outstanding</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->bills->take(10) as $bill)
                            <tr>
                                <td>{{ $bill->bill_number }}</td>
                                <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                                <td>${{ number_format($bill->total_amount, 2) }}</td>
                                <td>${{ number_format($bill->outstanding_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $bill->status == 'paid' ? 'success' : ($bill->status == 'overdue' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info">View</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No bills found for this customer.</p>
            @endif
        </div>
    </div>
</div>
@endsection
