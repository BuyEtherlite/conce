@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Allocation Details</h1>
                <div>
                    <a href="{{ route('housing.allocations.edit', $allocation) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('housing.allocations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Allocation Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $allocation->status === 'active' ? 'success' : ($allocation->status === 'terminated' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                            </p>
                            <p><strong>Allocated Date:</strong> {{ $allocation->allocated_date->format('M d, Y') }}</p>
                            <p><strong>Monthly Rent:</strong> ${{ number_format($allocation->monthly_rent, 2) }}</p>
                            <p><strong>Deposit Amount:</strong> ${{ number_format($allocation->deposit_amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Lease Start:</strong> {{ $allocation->lease_start_date->format('M d, Y') }}</p>
                            <p><strong>Lease End:</strong> {{ $allocation->lease_end_date->format('M d, Y') }}</p>
                            <p><strong>Property:</strong> {{ $allocation->property->property_name ?? 'N/A' }}</p>
                            <p><strong>Applicant:</strong> {{ $allocation->application->customer->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($allocation->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <p class="mt-2">{{ $allocation->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Property Details</h6>
                </div>
                <div class="card-body">
                    @if($allocation->property)
                        <p><strong>Property Name:</strong> {{ $allocation->property->property_name }}</p>
                        <p><strong>Address:</strong> {{ $allocation->property->address }}</p>
                        <p><strong>Property Type:</strong> {{ ucfirst($allocation->property->property_type) }}</p>
                        <p><strong>Bedrooms:</strong> {{ $allocation->property->bedrooms }}</p>
                        <p><strong>Bathrooms:</strong> {{ $allocation->property->bathrooms }}</p>
                    @else
                        <p class="text-muted">No property details available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
