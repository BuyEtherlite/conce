@extends('layouts.app')

@section('title', 'Customer Accounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-person-lines-fill"></i> Customer Accounts</h2>
                <a href="{{ route('billing.customers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> New Account
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Accounts</h5>
                                    <h3>{{ $customers->total() ?? 0 }}</h3>
                                </div>
                                <i class="bi bi-people fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Active Accounts</h5>
                                    <h3>{{ $customers->where('status', 'active')->count() }}</h3>
                                </div>
                                <i class="bi bi-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Customer name or account number">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Account Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="business" {{ request('type') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="organization" {{ request('type') == 'organization' ? 'selected' : '' }}>Organization</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-outline-primary d-block">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Account Number</th>
                                    <th>Customer Name</th>
                                    <th>Type</th>
                                    <th>Contact</th>
                                    <th>Current Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->account_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $customer->customer_name }}
                                        @if($customer->contact_person)
                                        <br><small class="text-muted">{{ $customer->contact_person }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($customer->account_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($customer->phone)
                                        <i class="bi bi-telephone"></i> {{ $customer->phone }}<br>
                                        @endif
                                        @if($customer->email)
                                        <i class="bi bi-envelope"></i> {{ $customer->email }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $customer->current_balance > 0 ? 'bg-danger' : 'bg-success' }}">
                                            ${{ number_format($customer->current_balance, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('billing.customers.show', $customer->id) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('billing.customers.edit', $customer->id) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No customer accounts found</td>
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
@endsection
