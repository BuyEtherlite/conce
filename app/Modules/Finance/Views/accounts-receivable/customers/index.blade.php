@extends('layouts.admin')

@section('title', 'AR Customers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>👥 AR Customers</h1>
                <a href="{{ route('finance.accounts-receivable.customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Customer
                </a>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="nav-link">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="nav-link active">Customers</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.payments') }}" class="nav-link">Payments</a>
                </li>
            </ul>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Credit Limit</th>
                                    <th>Invoices</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td>{{ $customer->customer_code ?? 'N/A' }}</td>
                                    <td>{{ $customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($customer->customer_type ?? 'N/A') }}</td>
                                    <td>{{ $customer->email ?? 'N/A' }}</td>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                    <td>${{ number_format($customer->credit_limit ?? 0, 2) }}</td>
                                    <td>{{ $customer->invoices_count ?? 0 }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary">View</a>
                                            <a href="#" class="btn btn-outline-secondary">Edit</a>
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

                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
