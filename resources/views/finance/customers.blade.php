@extends('layouts.app')

@section('page-title', 'Customers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💼 Customers</h4>
        <div>
            <a href="{{ route('finance.accounts-receivable.customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Customer
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="m-0">Customer List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_code }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>${{ number_format($customer->balance ?? 0, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $customer->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($customer->status ?? 'active') }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No customers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
