@extends('layouts.app')

@section('title', 'Edit Survey Project')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Survey Project</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.index') }}">Survey Services</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Project: {{ $project->project_number }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('survey.projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_number" class="form-label">Project Number</label>
                                    <input type="text" class="form-control" id="project_number" name="project_number" 
                                           value="{{ old('project_number', $project->project_number) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $project->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="survey_type_id" class="form-label">Survey Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('survey_type_id') is-invalid @enderror" 
                                            id="survey_type_id" name="survey_type_id" required>
                                        <option value="">Select Survey Type</option>
                                        @foreach($surveyTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                {{ old('survey_type_id', $project->survey_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} - ${{ number_format($type->base_cost, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('survey_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" 
                                            id="client_id" name="client_id" required>
                                        <option value="">Select Client</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" 
                                                {{ old('client_id', $project->client_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surveyor_id" class="form-label">Surveyor</label>
                                    <select class="form-select @error('surveyor_id') is-invalid @enderror" 
                                            id="surveyor_id" name="surveyor_id">
                                        <option value="">Select Surveyor</option>
                                        @foreach($surveyors as $surveyor)
                                            <option value="{{ $surveyor->id }}" 
                                                {{ old('surveyor_id', $project->surveyor_id) == $surveyor->id ? 'selected' : '' }}>
                                                {{ $surveyor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('surveyor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority', $project->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $project->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="survey_complete" {{ old('status', $project->status) == 'survey_complete' ? 'selected' : '' }}>Survey Complete</option>
                                        <option value="mapping_complete" {{ old('status', $project->status) == 'mapping_complete' ? 'selected' : '' }}>Mapping Complete</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Scheduled Date</label>
                                    <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           id="scheduled_date" name="scheduled_date" 
                                           value="{{ old('scheduled_date', $project->scheduled_date ? $project->scheduled_date->format('Y-m-d') : '') }}">
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="property_address" class="form-label">Property Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('property_address') is-invalid @enderror" 
                                      id="property_address" name="property_address" rows="3" required>{{ old('property_address', $project->property_address) }}</textarea>
                            @error('property_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_coordinates" class="form-label">Property Coordinates</label>
                                    <input type="text" class="form-control" id="property_coordinates" name="property_coordinates" 
                                           value="{{ old('property_coordinates', $project->property_coordinates) }}"
                                           placeholder="e.g., -26.2041, 28.0473">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="property_area" class="form-label">Property Area (sq m)</label>
                                    <input type="number" step="0.01" class="form-control" id="property_area" name="property_area" 
                                           value="{{ old('property_area', $project->property_area) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_cost" class="form-label">Estimated Cost <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                           id="estimated_cost" name="estimated_cost" 
                                           value="{{ old('estimated_cost', $project->estimated_cost) }}" required>
                                    @error('estimated_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="actual_cost" class="form-label">Actual Cost</label>
                                    <input type="number" step="0.01" class="form-control" id="actual_cost" name="actual_cost" 
                                           value="{{ old('actual_cost', $project->actual_cost) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="special_requirements" class="form-label">Special Requirements</label>
                            <textarea class="form-control" id="special_requirements" name="special_requirements" rows="3">{{ old('special_requirements', $project->special_requirements) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $project->notes) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('survey.projects.show', $project) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
