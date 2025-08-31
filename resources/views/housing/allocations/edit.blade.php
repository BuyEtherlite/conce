@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit Housing Allocation</h1>
                <a href="{{ route('housing.allocations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Allocations
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('housing.allocations.update', $allocation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="property_id">Property *</label>
                                    <select name="property_id" id="property_id" class="form-control" required>
                                        <option value="">Select Property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}" 
                                                {{ $allocation->property_id == $property->id ? 'selected' : '' }}>
                                                {{ $property->property_name }} - {{ $property->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="application_id">Application *</label>
                                    <select name="application_id" id="application_id" class="form-control" required>
                                        <option value="">Select Application</option>
                                        @foreach($applications as $application)
                                            <option value="{{ $application->id }}"
                                                {{ $allocation->application_id == $application->id ? 'selected' : '' }}>
                                                {{ $application->customer->name ?? 'Unknown' }} - {{ $application->application_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="allocated_date">Allocated Date *</label>
                                    <input type="date" name="allocated_date" id="allocated_date" 
                                           class="form-control" value="{{ $allocation->allocated_date->format('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="monthly_rent">Monthly Rent *</label>
                                    <input type="number" step="0.01" name="monthly_rent" id="monthly_rent" 
                                           class="form-control" value="{{ $allocation->monthly_rent }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deposit_amount">Deposit Amount *</label>
                                    <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" 
                                           class="form-control" value="{{ $allocation->deposit_amount }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="lease_start_date">Lease Start Date *</label>
                                    <input type="date" name="lease_start_date" id="lease_start_date" 
                                           class="form-control" value="{{ $allocation->lease_start_date->format('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="lease_end_date">Lease End Date *</label>
                                    <input type="date" name="lease_end_date" id="lease_end_date" 
                                           class="form-control" value="{{ $allocation->lease_end_date->format('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="active" {{ $allocation->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ $allocation->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="terminated" {{ $allocation->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control">{{ $allocation->notes }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Allocation
                            </button>
                            <a href="{{ route('housing.allocations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
