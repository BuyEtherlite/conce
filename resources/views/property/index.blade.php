@extends('layouts.admin')

@section('title', 'Property Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Property Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Property Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="text-muted fw-normal mt-0 text-truncate">Total Properties</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['total_properties']) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="ri-arrow-up-line me-1"></i>{{ number_format($stats['active_properties']) }} Active
                                </span>
                            </p>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-2 fs-2">
                                    <i class="ri-building-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="text-muted fw-normal mt-0 text-truncate">Total Value</h5>
                            <h3 class="my-2 py-1">R{{ number_format($stats['total_value'], 2) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Market Value
                                </span>
                            </p>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-2 fs-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="text-muted fw-normal mt-0 text-truncate">Residential</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['residential']) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="ri-home-line me-1"></i>Properties
                                </span>
                            </p>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-info rounded-2 fs-2">
                                    <i class="ri-home-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="text-muted fw-normal mt-0 text-truncate">Commercial</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['commercial']) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="ri-store-line me-1"></i>Properties
                                </span>
                            </p>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-warning rounded-2 fs-2">
                                    <i class="ri-store-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('property.create') }}" class="btn btn-primary w-100">
                                <i class="ri-add-line me-1"></i>Add Property
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('property.search') }}" class="btn btn-success w-100">
                                <i class="ri-search-line me-1"></i>Search Properties
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('property.categories.index') }}" class="btn btn-info w-100">
                                <i class="ri-list-line me-1"></i>Categories
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('property.reports') }}" class="btn btn-warning w-100">
                                <i class="ri-bar-chart-line me-1"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Properties List</h4>
                    <div>
                        <a href="{{ route('property.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add Property
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Property Code</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Address</th>
                                    <th>Owner</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $property)
                                <tr>
                                    <td>
                                        <strong>{{ $property->property_code }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('property.show', $property) }}" class="text-decoration-none">
                                            {{ $property->title }}
                                        </a>
                                        @if($property->erf_number)
                                        <br><small class="text-muted">ERF: {{ $property->erf_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $property->property_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $property->address }}<br>
                                        <small class="text-muted">{{ $property->suburb }}, {{ $property->city }}</small>
                                    </td>
                                    <td>
                                        @if($property->primaryOwner)
                                            {{ $property->primaryOwner->full_name }}
                                        @else
                                            <span class="text-muted">No owner</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($property->market_value)
                                            R{{ number_format($property->market_value, 2) }}
                                        @else
                                            <span class="text-muted">Not valued</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('property.show', $property) }}">
                                                    <i class="ri-eye-line me-2"></i>View
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('property.edit', $property) }}">
                                                    <i class="ri-edit-line me-2"></i>Edit
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('property.valuations.index', $property) }}">
                                                    <i class="ri-calculator-line me-2"></i>Valuations
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('property.leases.index', $property) }}">
                                                    <i class="ri-file-text-line me-2"></i>Leases
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-4 d-block mb-2"></i>
                                            No properties found
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($properties->hasPages())
                    <div class="mt-3">
                        {{ $properties->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
