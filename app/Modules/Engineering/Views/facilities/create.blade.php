@extends('layouts.app')

@section('page-title', 'Create New Facility')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Create New Facility</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Facilities
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facility_name" class="form-label">Facility Name *</label>
                                    <input type="text" class="form-control @error('facility_name') is-invalid @enderror" 
                                           id="facility_name" name="facility_name" value="{{ old('facility_name') }}" required>
                                    @error('facility_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facility_type" class="form-label">Facility Type *</label>
                                    <select class="form-select @error('facility_type') is-invalid @enderror" 
                                            id="facility_type" name="facility_type" required>
                                        <option value="">Select Type</option>
                                        @foreach($facilityTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('facility_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('facility_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                            @error('address')
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
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Capacity (People) *</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_name" class="form-label">Manager Name</label>
                                    <input type="text" class="form-control @error('manager_name') is-invalid @enderror" 
                                           id="manager_name" name="manager_name" value="{{ old('manager_name') }}">
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_contact" class="form-label">Manager Contact</label>
                                    <input type="text" class="form-control @error('manager_contact') is-invalid @enderror" 
                                           id="manager_contact" name="manager_contact" value="{{ old('manager_contact') }}">
                                    @error('manager_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pricing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
                                    <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" 
                                           step="0.01" min="0">
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="daily_rate" class="form-label">Daily Rate ($)</label>
                                    <input type="number" class="form-control @error('daily_rate') is-invalid @enderror" 
                                           id="daily_rate" name="daily_rate" value="{{ old('daily_rate') }}" 
                                           step="0.01" min="0">
                                    @error('daily_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Operating Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_time" class="form-label">Opening Time *</label>
                                    <input type="time" class="form-control @error('opening_time') is-invalid @enderror" 
                                           id="opening_time" name="opening_time" value="{{ old('opening_time', '08:00') }}" required>
                                    @error('opening_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="closing_time" class="form-label">Closing Time *</label>
                                    <input type="time" class="form-control @error('closing_time') is-invalid @enderror" 
                                           id="closing_time" name="closing_time" value="{{ old('closing_time', '18:00') }}" required>
                                    @error('closing_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Operating Days *</label>
                            <div class="row">
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="day_{{ $day }}" name="operating_days[]" value="{{ $day }}"
                                               {{ in_array($day, old('operating_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="day_{{ $day }}">
                                            {{ ucfirst($day) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('operating_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Amenities -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Amenities</h5>
                    </div>
                    <div class="card-body">
                        @foreach($amenities as $key => $amenity)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" 
                                   id="amenity_{{ $key }}" name="amenities[]" value="{{ $key }}"
                                   {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="amenity_{{ $key }}">
                                {{ $amenity }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" 
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Facility is Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="documents" class="form-label">Upload Documents</label>
                            <input type="file" class="form-control @error('documents.*') is-invalid @enderror" 
                                   id="documents" name="documents[]" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB each)
                            </div>
                            @error('documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="text-end">
                    <a href="{{ route('facilities.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Facility</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
