@extends('layouts.admin')

@section('page-title', 'Create New Zoning Record')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Zoning Record</h1>
        <div>
            <a href="{{ route('planning.zoning.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Zoning
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Zoning Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('planning.zoning.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="zone_code" class="form-label">Zone Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('zone_code') is-invalid @enderror" 
                                   id="zone_code" name="zone_code" value="{{ old('zone_code') }}" required>
                            @error('zone_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="zone_name" class="form-label">Zone Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('zone_name') is-invalid @enderror" 
                                   id="zone_name" name="zone_name" value="{{ old('zone_name') }}" required>
                            @error('zone_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="classification" class="form-label">Classification <span class="text-danger">*</span></label>
                            <select class="form-select @error('classification') is-invalid @enderror" 
                                    id="classification" name="classification" required>
                                <option value="">Select Classification</option>
                                <option value="residential" {{ old('classification') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('classification') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ old('classification') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                <option value="mixed_use" {{ old('classification') == 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                                <option value="agricultural" {{ old('classification') == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="special" {{ old('classification') == 'special' ? 'selected' : '' }}>Special</option>
                            </select>
                            @error('classification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="max_building_height" class="form-label">Max Building Height (m)</label>
                            <input type="number" class="form-control @error('max_building_height') is-invalid @enderror" 
                                   id="max_building_height" name="max_building_height" step="0.1" value="{{ old('max_building_height') }}">
                            @error('max_building_height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="max_coverage" class="form-label">Max Coverage (%)</label>
                            <input type="number" class="form-control @error('max_coverage') is-invalid @enderror" 
                                   id="max_coverage" name="max_coverage" min="0" max="100" value="{{ old('max_coverage') }}">
                            @error('max_coverage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="min_setback" class="form-label">Min Setback (m)</label>
                            <input type="number" class="form-control @error('min_setback') is-invalid @enderror" 
                                   id="min_setback" name="min_setback" step="0.1" value="{{ old('min_setback') }}">
                            @error('min_setback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="permitted_uses" class="form-label">Permitted Uses</label>
                            <textarea class="form-control @error('permitted_uses') is-invalid @enderror" 
                                      id="permitted_uses" name="permitted_uses" rows="3" 
                                      placeholder="List the permitted uses for this zone">{{ old('permitted_uses') }}</textarea>
                            @error('permitted_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Detailed description of the zoning classification">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="boundary_file" class="form-label">Boundary File</label>
                            <input type="file" class="form-control @error('boundary_file') is-invalid @enderror" 
                                   id="boundary_file" name="boundary_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">Upload boundary maps or documents (PDF, DOC, DOCX, JPG, PNG)</div>
                            @error('boundary_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ordinance_file" class="form-label">Ordinance File</label>
                            <input type="file" class="form-control @error('ordinance_file') is-invalid @enderror" 
                                   id="ordinance_file" name="ordinance_file" accept=".pdf,.doc,.docx">
                            <div class="form-text">Upload zoning ordinance documents (PDF, DOC, DOCX)</div>
                            @error('ordinance_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="effective_date" class="form-label">Effective Date</label>
                            <input type="date" class="form-control @error('effective_date') is-invalid @enderror" 
                                   id="effective_date" name="effective_date" value="{{ old('effective_date') }}">
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="review_date" class="form-label">Review Date</label>
                            <input type="date" class="form-control @error('review_date') is-invalid @enderror" 
                                   id="review_date" name="review_date" value="{{ old('review_date') }}">
                            <div class="form-text">Next scheduled review date for this zoning classification</div>
                            @error('review_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2" 
                                      placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('planning.zoning.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Zoning Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: .25rem solid #4e73df!important;
}
</style>
@endsection
