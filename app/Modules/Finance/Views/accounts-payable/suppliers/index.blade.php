@extends('layouts.admin')

@section('title', 'Suppliers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>🏢 Suppliers</h1>
                <a href="{{ route('finance.accounts-payable.suppliers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>New Supplier
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Suppliers List</h6>
                </div>
                <div class="card-body">
                    @if($suppliers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Supplier Code</th>
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Payment Terms</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->supplier_code }}</td>
                                        <td>
                                            <a href="{{ route('finance.accounts-payable.suppliers.show', $supplier) }}" 
                                               class="text-decoration-none font-weight-bold">
                                                {{ $supplier->supplier_name }}
                                            </a>
                                        </td>
                                        <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                                        <td>{{ $supplier->email ?? 'N/A' }}</td>
                                        <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                        <td>{{ $supplier->payment_terms }} days</td>
                                        <td>
                                            <span class="badge bg-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('finance.accounts-payable.suppliers.show', $supplier) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('finance.accounts-payable.suppliers.edit', $supplier) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('finance.accounts-payable.suppliers.destroy', $supplier) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $suppliers->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No suppliers found</h5>
                            <p class="text-muted">Start by adding your first supplier.</p>
                            <a href="{{ route('finance.accounts-payable.suppliers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Supplier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
