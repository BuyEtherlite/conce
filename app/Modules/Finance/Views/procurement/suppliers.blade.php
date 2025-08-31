@extends('layouts.admin')

@section('page-title', 'Supplier Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏢 Supplier Management</h4>
        <div>
            <a href="{{ route('finance.procurement.suppliers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Supplier
            </a>
            <a href="{{ route('finance.procurement.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Procurement
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="m-0">Suppliers List</h6>
        </div>
        <div class="card-body">
            @if($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Supplier Code</th>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Purchase Orders (This Year)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->supplier_code ?? 'N/A' }}</td>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                    <td>{{ $supplier->address ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $supplier->purchaseOrders->count() ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('finance.procurement.suppliers.show', $supplier->id) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('finance.procurement.suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$supplier->is_active)
                                                <form action="{{ route('finance.procurement.suppliers.activate', $supplier->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" 
                                                            onclick="return confirm('Are you sure you want to activate this supplier?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('finance.procurement.suppliers.deactivate', $supplier->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning" 
                                                            onclick="return confirm('Are you sure you want to deactivate this supplier?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5>No Suppliers Found</h5>
                    <p class="text-muted">Start by adding your first supplier to manage procurement.</p>
                    <a href="{{ route('finance.procurement.suppliers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Supplier
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
