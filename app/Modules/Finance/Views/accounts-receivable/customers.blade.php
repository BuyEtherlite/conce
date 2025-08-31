@extends('layouts.app')

@section('title', 'AR Customers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>👥 AR Customers</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus"></i> Add Customer
                </button>
            </div>

            <!-- Customer Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Total Customers</h5>
                            <h3>{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Active Customers</h5>
                            <h3>{{ number_format($stats['active_customers'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5>Outstanding Balance</h5>
                            <h3>R {{ number_format($stats['outstanding_balance'] ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Avg. Credit Days</h5>
                            <h3>{{ number_format($stats['avg_credit_days'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="credit_status" class="form-select">
                                <option value="">All Credit Status</option>
                                <option value="good" {{ request('credit_status') == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="overdue" {{ request('credit_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="bad_debt" {{ request('credit_status') == 'bad_debt' ? 'selected' : '' }}>Bad Debt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact Info</th>
                                    <th>Credit Limit</th>
                                    <th>Outstanding</th>
                                    <th>Status</th>
                                    <th>Last Invoice</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->business_name ? $customer->business_name : $customer->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $customer->account_number }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $customer->email }}<br>
                                            <small>{{ $customer->phone }}</small>
                                        </div>
                                    </td>
                                    <td>R {{ number_format($customer->credit_limit, 2) }}</td>
                                    <td>
                                        <span class="fw-bold {{ ($customer->outstanding_balance > 0) ? 'text-warning' : 'text-success' }}">
                                            R {{ number_format($customer->outstanding_balance, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'blocked' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $customer->last_invoice_date ? $customer->last_invoice_date->format('Y-m-d') : 'Never' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary">View</a>
                                            <a href="#" class="btn btn-outline-info">Statement</a>
                                            <a href="{{ route('finance.receivables.invoices.create') }}?customer={{ $customer->id }}" class="btn btn-outline-success">Invoice</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No customers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($customers) && $customers->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $customers->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('administration.crm.customers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Credit Limit</label>
                                <input type="number" name="credit_limit" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Terms (days)</label>
                                <input type="number" name="payment_terms" class="form-control" value="30">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection