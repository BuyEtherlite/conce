@extends('layouts.admin')

@section('title', 'Property Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Property Details - {{ $property->property_code }}</h5>
                    <div>
                        <a href="{{ route('housing.properties.edit', $property) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('housing.properties.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Property Code:</strong></td>
                                    <td>{{ $property->property_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Property Type:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($property->property_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @switch($property->status)
                                            @case('available')
                                                <span class="badge bg-success">Available</span>
                                                @break
                                            @case('occupied')
                                                <span class="badge bg-warning">Occupied</span>
                                                @break
                                            @case('maintenance')
                                                <span class="badge bg-danger">Maintenance</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge bg-secondary">Inactive</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bedrooms:</strong></td>
                                    <td>{{ $property->bedrooms }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bathrooms:</strong></td>
                                    <td>{{ $property->bathrooms }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Size:</strong></td>
                                    <td>{{ number_format($property->size_sqm, 2) }} sqm</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary">Financial Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Monthly Rental:</strong></td>
                                    <td>R {{ number_format($property->rental_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deposit Amount:</strong></td>
                                    <td>R {{ number_format($property->deposit_amount, 2) }}</td>
                                </tr>
                            </table>

                            <h6 class="text-primary mt-4">Location & Management</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Council:</strong></td>
                                    <td>{{ $property->council->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td>{{ $property->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Office:</strong></td>
                                    <td>{{ $property->office->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="text-primary">Address</h6>
                            <p>{{ $property->address }}</p>
                            <p>{{ $property->suburb }}, {{ $property->city }}, {{ $property->postal_code }}</p>
                        </div>
                    </div>

                    @if($property->description)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="text-primary">Description</h6>
                                <p>{{ $property->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($property->allocations && $property->allocations->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="text-primary">Current Allocations</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tenant</th>
                                                <th>Start Date</th>
                                                <th>Monthly Rent</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($property->allocations as $allocation)
                                                <tr>
                                                    <td>{{ $allocation->tenant->name ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->start_date ? \Carbon\Carbon::parse($allocation->start_date)->format('d M Y') : 'N/A' }}</td>
                                                    <td>R {{ number_format($allocation->monthly_rent ?? 0, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection