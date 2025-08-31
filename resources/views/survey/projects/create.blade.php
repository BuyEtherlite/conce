@extends('layouts.app')

@section('title', 'Create Survey Project')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">📐 Create Survey Project</h1>
                    <p class="text-muted">Set up a new land surveying project</p>
                </div>
                <div>
                    <a href="{{ route('survey.projects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('survey.projects.store') }}" method="POST">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Project Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Project Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="survey_type_id" class="form-label">Survey Type *</label>
                                    <select class="form-select @error('survey_type_id') is-invalid @enderror" 
                                            id="survey_type_id" name="survey_type_id" required>
                                        <option value="">Select Survey Type</option>
                                        @foreach($surveyTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('survey_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} - ${{ number_format($type->base_cost, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('survey_type_id')
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
                                    <label for="client_id" class="form-label">Client *</label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" 
                                            id="client_id" name="client_id" required>
                                        <option value="">Select Client</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('client_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->full_name }} - {{ $customer->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surveyor_id" class="form-label">Assigned Surveyor</label>
                                    <select class="form-select @error('surveyor_id') is-invalid @enderror" 
                                            id="surveyor_id" name="surveyor_id">
                                        <option value="">Select Surveyor</option>
                                        @foreach($surveyors as $surveyor)
                                            <option value="{{ $surveyor->id }}" {{ old('surveyor_id') == $surveyor->id ? 'selected' : '' }}>
                                                {{ $surveyor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('surveyor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="council_id" class="form-label">Council *</label>
                                    <select class="form-select @error('council_id') is-invalid @enderror" 
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department *</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" 
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
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Property Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="property_address" class="form-label">Property Address *</label>
                            <input type="text" class="form-control @error('property_address') is-invalid @enderror" 
                                   id="property_address" name="property_address" value="{{ old('property_address') }}" required>
                            @error('property_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_coordinates" class="form-label">Property Coordinates</label>
                                    <input type="text" class="form-control @error('property_coordinates') is-invalid @enderror" 
                                           id="property_coordinates" name="property_coordinates" value="{{ old('property_coordinates') }}"
                                           placeholder="e.g., -26.123456, 28.123456">
                                    @error('property_coordinates')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_area" class="form-label">Property Area (sqm)</label>
                                    <input type="number" step="0.01" class="form-control @error('property_area') is-invalid @enderror" 
                                           id="property_area" name="property_area" value="{{ old('property_area') }}">
                                    @error('property_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="property_boundaries" class="form-label">Property Boundaries</label>
                            <textarea class="form-control @error('property_boundaries') is-invalid @enderror" 
                                      id="property_boundaries" name="property_boundaries" rows="2" 
                                      placeholder="Describe property boundaries...">{{ old('property_boundaries') }}</textarea>
                            @error('property_boundaries')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Project Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority *</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="requested_date" class="form-label">Requested Date *</label>
                                    <input type="date" class="form-control @error('requested_date') is-invalid @enderror" 
                                           id="requested_date" name="requested_date" value="{{ old('requested_date', date('Y-m-d')) }}" required>
                                    @error('requested_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Scheduled Date</label>
                                    <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}">
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estimated_cost" class="form-label">Estimated Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost') }}">
                            </div>
                            @error('estimated_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_requirements" class="form-label">Special Requirements</label>
                            <textarea class="form-control @error('special_requirements') is-invalid @enderror" 
                                      id="special_requirements" name="special_requirements" rows="3" 
                                      placeholder="Any special requirements or considerations...">{{ old('special_requirements') }}</textarea>
                            @error('special_requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2" 
                                      placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('survey.projects.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Project
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
