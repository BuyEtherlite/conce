@extends('layouts.app')

@section('title', 'New Property Tax Assessment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">New Property Tax Assessment</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('property-tax.index') }}">Property Tax</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('property-tax.assessments.index') }}">Assessments</a></li>
                        <li class="breadcrumb-item active">New Assessment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Property Tax Assessment</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('property-tax.assessments.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Property ID</label>
                                    <input type="text" class="form-control @error('property_id') is-invalid @enderror" 
                                           name="property_id" value="{{ old('property_id') }}" required>
                                    @error('property_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
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
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Zone</label>
                                    <select class="form-select @error('zone_id') is-invalid @enderror" name="zone_id" required>
                                        <option value="">Select Zone</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->zone_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zone_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Assessed Value</label>
                                    <input type="number" step="0.01" class="form-control @error('assessed_value') is-invalid @enderror" 
                                           name="assessed_value" value="{{ old('assessed_value') }}" required>
                                    @error('assessed_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror" 
                                           name="tax_rate" value="{{ old('tax_rate') }}" required>
                                    @error('tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Assessment Date</label>
                                    <input type="date" class="form-control @error('assessment_date') is-invalid @enderror" 
                                           name="assessment_date" value="{{ old('assessment_date') }}" required>
                                    @error('assessment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Effective From</label>
                                    <input type="date" class="form-control @error('effective_from') is-invalid @enderror" 
                                           name="effective_from" value="{{ old('effective_from') }}" required>
                                    @error('effective_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Effective To</label>
                                    <input type="date" class="form-control @error('effective_to') is-invalid @enderror" 
                                           name="effective_to" value="{{ old('effective_to') }}">
                                    @error('effective_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Assessment</button>
                            <a href="{{ route('property-tax.assessments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
