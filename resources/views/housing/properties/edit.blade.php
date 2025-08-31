@extends('layouts.admin')

@section('title', 'Edit Property')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Property - {{ $property->property_code }}</h5>
                    <a href="{{ route('housing.properties.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Properties
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('housing.properties.update', $property) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_code" class="form-label">Property Code *</label>
                                    <input type="text" class="form-control @error('property_code') is-invalid @enderror"
                                           id="property_code" name="property_code" value="{{ old('property_code', $property->property_code) }}" required>
                                    @error('property_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_type" class="form-label">Property Type *</label>
                                    <select class="form-select @error('property_type') is-invalid @enderror"
                                            id="property_type" name="property_type" required>
                                        <option value="">Select Property Type</option>
                                        <option value="house" {{ old('property_type', $property->property_type) == 'house' ? 'selected' : '' }}>House</option>
                                        <option value="apartment" {{ old('property_type', $property->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="townhouse" {{ old('property_type', $property->property_type) == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                        <option value="flat" {{ old('property_type', $property->property_type) == 'flat' ? 'selected' : '' }}>Flat</option>
                                    </select>
                                    @error('property_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="2" required>{{ old('address', $property->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="suburb" class="form-label">Suburb *</label>
                                    <input type="text" class="form-control @error('suburb') is-invalid @enderror"
                                           id="suburb" name="suburb" value="{{ old('suburb', $property->suburb) }}" required>
                                    @error('suburb')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city', $property->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code *</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $property->postal_code) }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bedrooms" class="form-label">Bedrooms *</label>
                                    <input type="number" class="form-control @error('bedrooms') is-invalid @enderror"
                                           id="bedrooms" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="1" required>
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bathrooms" class="form-label">Bathrooms *</label>
                                    <input type="number" class="form-control @error('bathrooms') is-invalid @enderror"
                                           id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="1" required>
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="size_sqm" class="form-label">Size (sqm) *</label>
                                    <input type="number" class="form-control @error('size_sqm') is-invalid @enderror"
                                           id="size_sqm" name="size_sqm" value="{{ old('size_sqm', $property->size_sqm) }}" min="1" step="0.01" required>
                                    @error('size_sqm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status', $property->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ old('status', $property->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status', $property->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rental_amount" class="form-label">Monthly Rental (R) *</label>
                                    <input type="number" class="form-control @error('rental_amount') is-invalid @enderror"
                                           id="rental_amount" name="rental_amount" value="{{ old('rental_amount', $property->rental_amount) }}" min="0" step="0.01" required>
                                    @error('rental_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deposit_amount" class="form-label">Deposit Amount (R) *</label>
                                    <input type="number" class="form-control @error('deposit_amount') is-invalid @enderror"
                                           id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount', $property->deposit_amount) }}" min="0" step="0.01" required>
                                    @error('deposit_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="council_id" class="form-label">Council *</label>
                                    <select class="form-select @error('council_id') is-invalid @enderror"
                                            id="council_id" name="council_id" required>
                                        <option value="">Select Council</option>
                                        @foreach($councils as $council)
                                            <option value="{{ $council->id }}" {{ old('council_id', $property->council_id) == $council->id ? 'selected' : '' }}>
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
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department *</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror"
                                            id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $property->department_id) == $department->id ? 'selected' : '' }}>
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
                                <div class="mb-3">
                                    <label for="office_id" class="form-label">Office *</label>
                                    <select class="form-select @error('office_id') is-invalid @enderror"
                                            id="office_id" name="office_id" required>
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id', $property->office_id) == $office->id ? 'selected' : '' }}>
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

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('housing.properties.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection