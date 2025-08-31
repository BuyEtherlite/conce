@extends('layouts.app')

@section('page-title', 'Property Valuations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">🏠 Property Valuations</h1>
        <div class="btn-group">
            <a href="{{ route('property-tax.valuations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Valuation
            </a>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-1"></i>Import Valuations
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('property-tax.valuations') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="property_code">Property Code</label>
                            <input type="text" class="form-control" id="property_code" name="property_code" 
                                   value="{{ request('property_code') }}" placeholder="Search by property code...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="owner_name">Owner Name</label>
                            <input type="text" class="form-control" id="owner_name" name="owner_name" 
                                   value="{{ request('owner_name') }}" placeholder="Search by owner name...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tax_category">Tax Category</label>
                            <select class="form-control" id="tax_category" name="tax_category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('tax_category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('property-tax.valuations') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Valuations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Property Valuations</h6>
        </div>
        <div class="card-body">
            @if($valuations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Valuation #</th>
                                <th>Property Code</th>
                                <th>Owner</th>
                                <th>Address</th>
                                <th>Municipal Value</th>
                                <th>Market Value</th>
                                <th>Category</th>
                                <th>Zone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($valuations as $valuation)
                            <tr>
                                <td>{{ $valuation->valuation_number }}</td>
                                <td>{{ $valuation->property_code }}</td>
                                <td>
                                    <div>{{ $valuation->owner_name }}</div>
                                    <small class="text-muted">{{ $valuation->owner_id_number }}</small>
                                </td>
                                <td>
                                    <div>{{ $valuation->property_address }}</div>
                                    <small class="text-muted">{{ $valuation->suburb }}, {{ $valuation->city }}</small>
                                </td>
                                <td>R {{ number_format($valuation->municipal_value, 2) }}</td>
                                <td>R {{ number_format($valuation->market_value, 2) }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $valuation->taxCategory->name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $valuation->taxZone->name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $valuation->status == 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($valuation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-success" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$valuation->assessments()->where('tax_year', date('Y'))->exists())
                                            <a href="{{ route('property-tax.assessments.create', ['valuation' => $valuation->id]) }}" 
                                               class="btn btn-info" title="Create Assessment">
                                                <i class="fas fa-calculator"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $valuations->firstItem() }} to {{ $valuations->lastItem() }} 
                        of {{ $valuations->total() }} results
                    </div>
                    <div>
                        {{ $valuations->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                    <h5>No Property Valuations Found</h5>
                    <p class="text-muted">Start by creating your first property valuation.</p>
                    <a href="{{ route('property-tax.valuations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Valuation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Valuations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Choose CSV File</label>
                        <input type="file" class="form-control" id="import_file" accept=".csv">
                        <div class="form-text">
                            Upload a CSV file with property valuation data. 
                            <a href="#" class="text-primary">Download sample template</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Import</button>
            </div>
        </div>
    </div>
</div>
@endsection
