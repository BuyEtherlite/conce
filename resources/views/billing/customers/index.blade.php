@extends('layouts.admin')

@section('title', 'Customer Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Customer Accounts</h1>
    <a href="{{ route('billing.customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Customer
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
        <h5 class="card-title mb-0">Customer List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Account Type</th>
                        <th>Contact</th>
                        <th>Council</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <strong>{{ $customer->customer_name }}</strong>
                            @if($customer->contact_person)
                                <br><small class="text-muted">{{ $customer->contact_person }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($customer->account_type) }}</span>
                        </td>
                        <td>
                            @if($customer->phone)
                                <div><i class="fas fa-phone"></i> {{ $customer->phone }}</div>
                            @endif
                            @if($customer->email)
                                <div><i class="fas fa-envelope"></i> {{ $customer->email }}</div>
                            @endif
                        </td>
                        <td>{{ $customer->council->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }}">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('billing.customers.show', $customer->id) }}" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('billing.customers.edit', $customer->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Delete"
                                        onclick="confirmDelete({{ $customer->id }}, '{{ $customer->customer_name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="d-flex justify-content-center">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete customer <strong id="customerName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(customerId, customerName) {
    document.getElementById('customerName').textContent = customerName;
    document.getElementById('deleteForm').action = '/billing/customers/' + customerId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection