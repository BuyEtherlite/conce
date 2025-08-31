@extends('layouts.app')

@section('page-title', 'Create Housing Allocation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Housing Allocation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('housing.allocations.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="housing_application_id">Housing Application *</label>
                                    <select class="form-control @error('housing_application_id') is-invalid @enderror" 
                                            id="housing_application_id" name="housing_application_id" required>
                                        <option value="">Select Application</option>
                                        @foreach($applications as $application)
                                            <option value="{{ $application->id }}" {{ old('housing_application_id') == $application->id ? 'selected' : '' }}>
                                                APP-{{ $application->id }} - {{ $application->applicant_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('housing_application_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="property_id">Property *</label>
                                    <select class="form-control @error('property_id') is-invalid @enderror" 
                                            id="property_id" name="property_id" required>
                                        <option value="">Select Property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                                {{ $property->address }} ({{ $property->property_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="office_id">Office *</label>
                                    <select class="form-control @error('office_id') is-invalid @enderror" 
                                            id="office_id" name="office_id" required>
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                {{ $office->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('office_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allocation_date">Allocation Date *</label>
                                    <input type="date" class="form-control @error('allocation_date') is-invalid @enderror" 
                                           id="allocation_date" name="allocation_date" value="{{ old('allocation_date', date('Y-m-d')) }}" required>
                                    @error('allocation_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_rent">Monthly Rent *</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('monthly_rent') is-invalid @enderror" 
                                           id="monthly_rent" name="monthly_rent" value="{{ old('monthly_rent') }}" required>
                                    @error('monthly_rent')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deposit_amount">Deposit Amount *</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                           id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount') }}" required>
                                    @error('deposit_amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lease_start_date">Lease Start Date *</label>
                                    <input type="date" class="form-control @error('lease_start_date') is-invalid @enderror" 
                                           id="lease_start_date" name="lease_start_date" value="{{ old('lease_start_date') }}" required>
                                    @error('lease_start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lease_end_date">Lease End Date *</label>
                                    <input type="date" class="form-control @error('lease_end_date') is-invalid @enderror" 
                                           id="lease_end_date" name="lease_end_date" value="{{ old('lease_end_date') }}" required>
                                    @error('lease_end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="terms_conditions">Terms & Conditions</label>
                            <textarea class="form-control @error('terms_conditions') is-invalid @enderror" 
                                      id="terms_conditions" name="terms_conditions" rows="4">{{ old('terms_conditions') }}</textarea>
                            @error('terms_conditions')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Allocation
                            </button>
                            <a href="{{ route('housing.allocations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
