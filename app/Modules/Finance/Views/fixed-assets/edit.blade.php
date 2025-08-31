@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit text-warning"></i> Edit Fixed Asset
                    </h3>
                    <div>
                        <a href="{{ route('finance.fixed-assets.show', $asset->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('finance.fixed-assets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.fixed-assets.update', $asset->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asset_name') is-invalid @enderror" 
                                           id="asset_name" name="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" required>
                                    @error('asset_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
                                    <select class="form-select @error('location_id') is-invalid @enderror" 
                                            id="location_id" name="location_id" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" 
                                                {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="useful_life_years" class="form-label">Useful Life (Years) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('useful_life_years') is-invalid @enderror" 
                                           id="useful_life_years" name="useful_life_years" 
                                           value="{{ old('useful_life_years', $asset->useful_life_years) }}" required>
                                    @error('useful_life_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="residual_value" class="form-label">Residual Value</label>
                                    <input type="number" step="0.01" class="form-control @error('residual_value') is-invalid @enderror" 
                                           id="residual_value" name="residual_value" 
                                           value="{{ old('residual_value', $asset->residual_value) }}">
                                    @error('residual_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_name" class="form-label">Supplier Name</label>
                                    <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" 
                                           id="supplier_name" name="supplier_name" 
                                           value="{{ old('supplier_name', $asset->supplier_name) }}">
                                    @error('supplier_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                           id="warranty_expiry" name="warranty_expiry" 
                                           value="{{ old('warranty_expiry', $asset->warranty_expiry) }}">
                                    @error('warranty_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="disposed" {{ old('status', $asset->status) == 'disposed' ? 'selected' : '' }}>
                                            Disposed
                                        </option>
                                        <option value="written_off" {{ old('status', $asset->status) == 'written_off' ? 'selected' : '' }}>
                                            Written Off
                                        </option>
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
                                      id="description" name="description" rows="3">{{ old('description', $asset->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
