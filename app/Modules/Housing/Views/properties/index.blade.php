@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">🏠 Housing Properties</h1>
        <a href="{{ route('housing.properties.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Property
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Properties</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Occupied</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['occupied'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Maintenance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['maintenance'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Properties List</h6>
        </div>
        <div class="card-body">
            @if($properties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Property Code</th>
                                <th>Address</th>
                                <th>Type</th>
                                <th>Bedrooms</th>
                                <th>Bathrooms</th>
                                <th>Rent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                            <tr>
                                <td>
                                    <strong>{{ $property->property_code }}</strong>
                                </td>
                                <td>
                                    <div>{{ $property->address }}</div>
                                    <small class="text-muted">{{ $property->suburb }}, {{ $property->city }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($property->property_type) }}</span>
                                </td>
                                <td>{{ $property->bedrooms }}</td>
                                <td>{{ $property->bathrooms }}</td>
                                <td>R{{ number_format($property->rental_amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $property->status_badge }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('housing.properties.show', $property) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('housing.properties.edit', $property) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('housing.properties.destroy', $property) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-home fa-3x text-gray-300 mb-3"></i>
                    <h4>No Properties Found</h4>
                    <p class="text-muted">Start by adding your first property to the system.</p>
                    <a href="{{ route('housing.properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Property
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection