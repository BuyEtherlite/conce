@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-tags"></i> Event Categories</h2>
                <a href="{{ route('events.permits.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Permits
                </a>
            </div>

            <div class="row">
                <!-- Add New Category -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Add New Category</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('events.categories.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fee_amount" class="form-label">Base Fee Amount (R) *</label>
                                    <input type="number" class="form-control @error('fee_amount') is-invalid @enderror" 
                                           id="fee_amount" name="fee_amount" value="{{ old('fee_amount') }}" 
                                           min="0" step="0.01" required>
                                    @error('fee_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> Add Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Categories List -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Event Categories</h5>
                        </div>
                        <div class="card-body">
                            @if($categories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Base Fee</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categories as $category)
                                            <tr>
                                                <td>
                                                    <strong>{{ $category->name }}</strong>
                                                </td>
                                                <td>{{ $category->description ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-success">R{{ number_format($category->fee_amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-warning" onclick="editCategory({{ $category->id }})">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory({{ $category->id }})">
                                                            <i class="bi bi-trash"></i>
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
                                    {{ $categories->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-tags fs-1 text-muted mb-3"></i>
                                    <h5>No categories found</h5>
                                    <p class="text-muted">Add your first event category to get started.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(id) {
    // Implementation for editing category
    alert('Edit functionality to be implemented');
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        // Implementation for deleting category
        alert('Delete functionality to be implemented');
    }
}
</script>
@endsection
