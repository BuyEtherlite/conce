@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus text-primary"></i> Add New Fixed Asset
                    </h3>
                    <a href="{{ route('finance.fixed-assets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Assets
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.fixed-assets.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_tag" class="form-label">Asset Tag <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asset_tag') is-invalid @enderror" 
                                           id="asset_tag" name="asset_tag" value="{{ old('asset_tag') }}" required>
                                    @error('asset_tag')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asset_name') is-invalid @enderror" 
                                           id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required>
                                    @error('asset_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
                                    <select class="form-select @error('location_id') is-invalid @enderror" 
                                            id="location_id" name="location_id" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}" required>
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_cost" class="form-label">Purchase Cost <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('purchase_cost') is-invalid @enderror" 
                                           id="purchase_cost" name="purchase_cost" value="{{ old('purchase_cost') }}" required>
                                    @error('purchase_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="useful_life_years" class="form-label">Useful Life (Years) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('useful_life_years') is-invalid @enderror" 
                                           id="useful_life_years" name="useful_life_years" value="{{ old('useful_life_years') }}" required>
                                    @error('useful_life_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="depreciation_method" class="form-label">Depreciation Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('depreciation_method') is-invalid @enderror" 
                                            id="depreciation_method" name="depreciation_method" required>
                                        <option value="">Select Method</option>
                                        <option value="straight_line" {{ old('depreciation_method') == 'straight_line' ? 'selected' : '' }}>
                                            Straight Line
                                        </option>
                                        <option value="declining_balance" {{ old('depreciation_method') == 'declining_balance' ? 'selected' : '' }}>
                                            Declining Balance
                                        </option>
                                        <option value="units_production" {{ old('depreciation_method') == 'units_production' ? 'selected' : '' }}>
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
                                <div class="mb-3">
                                    <label for="residual_value" class="form-label">Residual Value</label>
                                    <input type="number" step="0.01" class="form-control @error('residual_value') is-invalid @enderror" 
                                           id="residual_value" name="residual_value" value="{{ old('residual_value', 0) }}">
                                    @error('residual_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_name" class="form-label">Supplier Name</label>
                                    <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" 
                                           id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}">
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
                                           id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                                    @error('warranty_expiry')
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

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
