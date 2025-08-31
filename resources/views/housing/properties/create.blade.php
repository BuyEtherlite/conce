@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">🏠 Add New Property</h1>
        <a href="{{ route('housing.properties.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Properties
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Property Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('housing.properties.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="property_code">Property Code *</label>
                                    <input type="text" class="form-control @error('property_code') is-invalid @enderror" 
                                           id="property_code" name="property_code" value="{{ old('property_code') }}" required>
                                    @error('property_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="property_type">Property Type *</label>
                                    <select class="form-control @error('property_type') is-invalid @enderror" 
                                            id="property_type" name="property_type" required>
                                        <option value="">Select Type</option>
                                        <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>House</option>
                                        <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="townhouse" {{ old('property_type') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                        <option value="flat" {{ old('property_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                                    </select>
                                    @error('property_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="suburb">Suburb *</label>
                                    <input type="text" class="form-control @error('suburb') is-invalid @enderror" 
                                           id="suburb" name="suburb" value="{{ old('suburb') }}" required>
                                    @error('suburb')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal_code">Postal Code *</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bedrooms">Bedrooms *</label>
                                    <input type="number" class="form-control @error('bedrooms') is-invalid @enderror" 
                                           id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}" min="1" required>
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bathrooms">Bathrooms *</label>
                                    <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" 
                                           id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" min="1" required>
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="size_sqm">Size (sqm) *</label>
                                    <input type="number" step="0.01" class="form-control @error('size_sqm') is-invalid @enderror" 
                                           id="size_sqm" name="size_sqm" value="{{ old('size_sqm') }}" min="1" required>
                                    @error('size_sqm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rental_amount">Monthly Rent (R) *</label>
                                    <input type="number" step="0.01" class="form-control @error('rental_amount') is-invalid @enderror" 
                                           id="rental_amount" name="rental_amount" value="{{ old('rental_amount') }}" min="0" required>
                                    @error('rental_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deposit_amount">Deposit Amount (R) *</label>
                                    <input type="number" step="0.01" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                           id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount') }}" min="0" required>
                                    @error('deposit_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="council_id">Council *</label>
                                    <select class="form-control @error('council_id') is-invalid @enderror" 
                                            id="council_id" name="council_id" required>
                                        <option value="">Select Council</option>
                                        @foreach($councils as $council)
                                            <option value="{{ $council->id }}" {{ old('council_id') == $council->id ? 'selected' : '' }}>
                                                {{ $council->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('council_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department_id">Department *</label>
                                    <select class="form-control @error('department_id') is-invalid @enderror" 
                                            id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('housing.properties.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📋 Property Guidelines</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Please ensure all information is accurate and complete:</p>
                    <ul class="list-unstyled small text-muted">
                        <li>✓ Property code must be unique</li>
                        <li>✓ Complete address details required</li>
                        <li>✓ Accurate room counts</li>
                        <li>✓ Realistic rental amounts</li>
                        <li>✓ Proper organizational assignment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
