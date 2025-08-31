@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Register New Asset</h1>
                <a href="{{ route('finance.assets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Assets
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('finance.assets.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_tag">Asset Tag <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asset_tag') is-invalid @enderror" 
                                           id="asset_tag" name="asset_tag" value="{{ old('asset_tag', $nextAssetTag) }}" required>
                                    @error('asset_tag')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_name">Asset Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asset_name') is-invalid @enderror" 
                                           id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required>
                                    @error('asset_name')
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_category">Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('asset_category') is-invalid @enderror" 
                                            id="asset_category" name="asset_category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('asset_category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asset_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location <span class="text-danger">*</span></label>
                                    <select class="form-control @error('location') is-invalid @enderror" 
                                            id="location" name="location" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="acquisition_date">Acquisition Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('acquisition_date') is-invalid @enderror" 
                                           id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date') }}" required>
                                    @error('acquisition_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="acquisition_cost">Acquisition Cost ($) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('acquisition_cost') is-invalid @enderror" 
                                           id="acquisition_cost" name="acquisition_cost" value="{{ old('acquisition_cost') }}" 
                                           step="0.01" min="0" required>
                                    @error('acquisition_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="useful_life_years">Useful Life (Years) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('useful_life_years') is-invalid @enderror" 
                                           id="useful_life_years" name="useful_life_years" value="{{ old('useful_life_years') }}" 
                                           min="1" required>
                                    @error('useful_life_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="depreciation_method">Depreciation Method <span class="text-danger">*</span></label>
                                    <select class="form-control @error('depreciation_method') is-invalid @enderror" 
                                            id="depreciation_method" name="depreciation_method" required>
                                        <option value="">Select Method</option>
                                        <option value="straight_line" {{ old('depreciation_method') == 'straight_line' ? 'selected' : '' }}>
                                            Straight Line
                                        </option>
                                        <option value="declining_balance" {{ old('depreciation_method') == 'declining_balance' ? 'selected' : '' }}>
                                            Declining Balance
                                        </option>
                                        <option value="units_of_production" {{ old('depreciation_method') == 'units_of_production' ? 'selected' : '' }}>
                                            Units of Production
                                        </option>
                                    </select>
                                    @error('depreciation_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="condition">Condition <span class="text-danger">*</span></label>
                                    <select class="form-control @error('condition') is-invalid @enderror" 
                                            id="condition" name="condition" required>
                                        <option value="">Select Condition</option>
                                        <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                                        <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                        <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Asset
                            </button>
                            <a href="{{ route('finance.assets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
