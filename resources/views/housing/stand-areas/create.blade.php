@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Create New Stand Area</h2>
                <a href="{{ route('housing.stand-areas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Stand Areas
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('housing.stand-areas.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Basic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Area Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">Area Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sector_type" class="form-label">Sector Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sector_type') is-invalid @enderror" 
                                            id="sector_type" name="sector_type" required>
                                        <option value="">Select Sector Type</option>
                                        @foreach($sectorTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('sector_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sector_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}" required>
                                    @error('location')
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
                            </div>

                            <!-- Stand Details -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Stand Details</h5>

                                <div class="mb-3">
                                    <label for="total_stands" class="form-label">Total Stands <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_stands') is-invalid @enderror" 
                                           id="total_stands" name="total_stands" value="{{ old('total_stands') }}" required>
                                    @error('total_stands')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stand_size_min_sqm" class="form-label">Min Stand Size (sqm)</label>
                                            <input type="number" step="0.01" class="form-control @error('stand_size_min_sqm') is-invalid @enderror" 
                                                   id="stand_size_min_sqm" name="stand_size_min_sqm" value="{{ old('stand_size_min_sqm') }}">
                                            @error('stand_size_min_sqm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stand_size_max_sqm" class="form-label">Max Stand Size (sqm)</label>
                                            <input type="number" step="0.01" class="form-control @error('stand_size_max_sqm') is-invalid @enderror" 
                                                   id="stand_size_max_sqm" name="stand_size_max_sqm" value="{{ old('stand_size_max_sqm') }}">
                                            @error('stand_size_max_sqm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="price_per_sqm" class="form-label">Price per sqm</label>
                                    <input type="number" step="0.01" class="form-control @error('price_per_sqm') is-invalid @enderror" 
                                           id="price_per_sqm" name="price_per_sqm" value="{{ old('price_per_sqm') }}">
                                    @error('price_per_sqm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="development_status" class="form-label">Development Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('development_status') is-invalid @enderror" 
                                            id="development_status" name="development_status" required>
                                        <option value="">Select Status</option>
                                        @foreach($developmentStatuses as $key => $value)
                                            <option value="{{ $key }}" {{ old('development_status') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('development_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Available Utilities</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="utilities_available[]" value="water" id="utility_water">
                                                <label class="form-check-label" for="utility_water">Water</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="utilities_available[]" value="electricity" id="utility_electricity">
                                                <label class="form-check-label" for="utility_electricity">Electricity</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="utilities_available[]" value="sewer" id="utility_sewer">
                                                <label class="form-check-label" for="utility_sewer">Sewer</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="utilities_available[]" value="internet" id="utility_internet">
                                                <label class="form-check-label" for="utility_internet">Internet</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('housing.stand-areas.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create Stand Area</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
