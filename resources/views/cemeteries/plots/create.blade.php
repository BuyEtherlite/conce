@extends('layouts.admin')

@section('page-title', 'Add Cemetery Plot')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Add New Cemetery Plot</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cemeteries.plots.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plot_number" class="form-label">Plot Number *</label>
                                    <input type="text" class="form-control @error('plot_number') is-invalid @enderror" 
                                           id="plot_number" name="plot_number" value="{{ old('plot_number') }}" required>
                                    @error('plot_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section" class="form-label">Section *</label>
                                    <select class="form-select @error('section') is-invalid @enderror" 
                                            id="section" name="section" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->section_name }}" {{ old('section') == $section->section_name ? 'selected' : '' }}>
                                                {{ $section->section_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="size" class="form-label">Size *</label>
                                    <select class="form-select @error('size') is-invalid @enderror" 
                                            id="size" name="size" required>
                                        <option value="">Select Size</option>
                                        <option value="Single" {{ old('size') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Double" {{ old('size') == 'Double' ? 'selected' : '' }}>Double</option>
                                        <option value="Family" {{ old('size') == 'Family' ? 'selected' : '' }}>Family</option>
                                    </select>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gps_latitude" class="form-label">GPS Latitude</label>
                                    <input type="number" class="form-control @error('gps_latitude') is-invalid @enderror" 
                                           id="gps_latitude" name="gps_latitude" value="{{ old('gps_latitude') }}" 
                                           step="0.00000001" min="-90" max="90">
                                    @error('gps_latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gps_longitude" class="form-label">GPS Longitude</label>
                                    <input type="number" class="form-control @error('gps_longitude') is-invalid @enderror" 
                                           id="gps_longitude" name="gps_longitude" value="{{ old('gps_longitude') }}" 
                                           step="0.00000001" min="-180" max="180">
                                    @error('gps_longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('cemeteries.plots.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Plot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
