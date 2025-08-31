@extends('layouts.app')

@section('page-title', 'Customer Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>👥 Customer Management</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('administration.index') }}">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.crm.index') }}">CRM</a></li>
                    <li class="breadcrumb-item active">Customers</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('administration.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Customer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Customers</h5>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Customer #</th>
                                <th>Full Name</th>
                                <th>ID Number</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Council</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->customer_number }}</td>
                                <td>{{ $customer->full_name }}</td>
                                <td>{{ $customer->id_number }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    <span class="badge bg-{{ $customer->status === 'active' ? 'success' : ($customer->status === 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>{{ $customer->council->name ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('administration.customers.show', $customer) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('administration.customers.edit', $customer) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No Customers Found</h5>
                    <p class="text-muted">Start by adding your first customer.</p>
                    <a href="{{ route('administration.customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Customer
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
