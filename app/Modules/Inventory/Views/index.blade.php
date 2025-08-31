@extends('layouts.admin')

@section('page-title', 'Inventory Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Inventory Management</h1>
                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add New Item
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_items'] ?? 0) }}</h4>
                            <p class="mb-0">Total Items</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white">
                    <a href="{{ route('inventory.reports.total') }}" class="card-link text-white text-decoration-none">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>${{ number_format($stats['total_value'] ?? 0, 2) }}</h4>
                            <p class="mb-0">Total Value</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white">
                    <a href="{{ route('inventory.reports.valuation') }}" class="card-link text-white text-decoration-none">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['low_stock'] ?? 0) }}</h4>
                            <p class="mb-0">Low Stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white">
                    <a href="{{ route('inventory.reports.low-stock') }}" class="card-link text-white text-decoration-none">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['out_of_stock'] ?? 0) }}</h4>
                            <p class="mb-0">Out of Stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white">
                    <a href="{{ route('inventory.reports.out-of-stock') }}" class="card-link text-white text-decoration-none">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search items...">
                </div>
                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Stock Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="in-stock" {{ request('status') == 'in-stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}" placeholder="Storage location...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Inventory Items</h5>
        </div>
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min Stock</th>
                                <th>Max Stock</th>
                                <th>Unit Cost</th>
                                <th>Total Value</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->name }}</strong>
                                    @if($item->description)
                                        <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->category }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold {{ $item->isOutOfStock() ? 'text-danger' : ($item->isLowStock() ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($item->current_stock) }}
                                    </span>
                                    <small class="text-muted">{{ $item->unit_of_measure }}</small>
                                </td>
                                <td>{{ number_format($item->minimum_stock) }}</td>
                                <td>{{ number_format($item->maximum_stock) }}</td>
                                <td>${{ number_format($item->unit_cost, 2) }}</td>
                                <td>${{ number_format($item->total_value, 2) }}</td>
                                <td>{{ $item->location ?? 'Not Set' }}</td>
                                <td>
                                    @if($item->isOutOfStock())
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($item->isLowStock())
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif

                                    @if($item->isExpiringSoon())
                                        <br><span class="badge bg-warning mt-1">Expiring Soon</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('inventory.show', $item) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.edit', $item) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $item->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-box fs-1 text-muted mb-3"></i>
                    <h5>No inventory items found</h5>
                    <p class="text-muted">Start by adding your first inventory item.</p>
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Add First Item
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this inventory item? This action cannot be undone.
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
function confirmDelete(itemId) {
    const form = document.getElementById('deleteForm');
    form.action = `/inventory/${itemId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection