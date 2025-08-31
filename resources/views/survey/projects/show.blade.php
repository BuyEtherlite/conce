@extends('layouts.app')

@section('title', 'Survey Project Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Survey Project: {{ $project->project_number }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.index') }}">Survey Services</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active">{{ $project->project_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Information</h4>
                    <div class="card-header-pills">
                        <span class="badge badge-{{ $project->status_color }}">{{ ucwords(str_replace('_', ' ', $project->status)) }}</span>
                        <span class="badge badge-{{ $project->priority_color }}">{{ ucfirst($project->priority) }} Priority</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Title:</dt>
                                <dd class="col-sm-8">{{ $project->title }}</dd>
                                
                                <dt class="col-sm-4">Survey Type:</dt>
                                <dd class="col-sm-8">{{ $project->surveyType->name ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-4">Client:</dt>
                                <dd class="col-sm-8">{{ $project->client->name ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-4">Surveyor:</dt>
                                <dd class="col-sm-8">{{ $project->surveyor->name ?? 'Unassigned' }}</dd>
                                
                                <dt class="col-sm-4">Property Address:</dt>
                                <dd class="col-sm-8">{{ $project->property_address }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Scheduled Date:</dt>
                                <dd class="col-sm-8">{{ $project->scheduled_date ? $project->scheduled_date->format('Y-m-d') : 'Not scheduled' }}</dd>
                                
                                <dt class="col-sm-4">Completion Date:</dt>
                                <dd class="col-sm-8">{{ $project->completed_date ? $project->completed_date->format('Y-m-d') : 'Not completed' }}</dd>
                                
                                <dt class="col-sm-4">Estimated Cost:</dt>
                                <dd class="col-sm-8">${{ number_format($project->estimated_cost, 2) }}</dd>
                                
                                <dt class="col-sm-4">Actual Cost:</dt>
                                <dd class="col-sm-8">${{ number_format($project->actual_cost ?? 0, 2) }}</dd>
                                
                                <dt class="col-sm-4">Property Area:</dt>
                                <dd class="col-sm-8">{{ $project->property_area ? number_format($project->property_area, 2) . ' sq m' : 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($project->description)
                    <div class="mt-3">
                        <h6>Description</h6>
                        <p>{{ $project->description }}</p>
                    </div>
                    @endif

                    @if($project->special_requirements)
                    <div class="mt-3">
                        <h6>Special Requirements</h6>
                        <p>{{ $project->special_requirements }}</p>
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('survey.projects.edit', $project) }}" class="btn btn-warning">Edit Project</a>
                        <a href="{{ route('survey.projects.index') }}" class="btn btn-secondary">Back to Projects</a>
                    </div>
                </div>
            </div>

            <!-- Measurements -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Survey Measurements</h4>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMeasurementModal">
                        Add Measurement
                    </button>
                </div>
                <div class="card-body">
                    @if($project->measurements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Point Name</th>
                                    <th>X Coordinate</th>
                                    <th>Y Coordinate</th>
                                    <th>Elevation</th>
                                    <th>Point Type</th>
                                    <th>Measured Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->measurements as $measurement)
                                <tr>
                                    <td>{{ $measurement->point_name }}</td>
                                    <td>{{ $measurement->x_coordinate }}</td>
                                    <td>{{ $measurement->y_coordinate }}</td>
                                    <td>{{ $measurement->elevation }}</td>
                                    <td>{{ ucfirst($measurement->point_type) }}</td>
                                    <td>{{ $measurement->measured_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No measurements recorded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Documents</h4>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        Upload Document
                    </button>
                </div>
                <div class="card-body">
                    @if($project->documents->count() > 0)
                    <div class="row">
                        @foreach($project->documents as $document)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $document->title }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</small><br>
                                        <small class="text-muted">{{ $document->created_at->format('Y-m-d') }}</small>
                                    </p>
                                    <a href="{{ route('survey.documents.download', $document) }}" class="btn btn-sm btn-outline-primary">Download</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No documents uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Project Status -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Project Created</h6>
                                <p class="text-muted small">{{ $project->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($project->scheduled_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Scheduled</h6>
                                <p class="text-muted small">{{ $project->scheduled_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->completed_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Completed</h6>
                                <p class="text-muted small">{{ $project->completed_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Financial Summary</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary">${{ number_format($project->total_fees, 2) }}</h4>
                                <p class="text-muted mb-0">Total Fees</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success">${{ number_format($project->paid_fees, 2) }}</h4>
                                <p class="text-muted mb-0">Paid</p>
                            </div>
                        </div>
                    </div>
                    @if($project->outstanding_fees > 0)
                    <div class="alert alert-warning">
                        <strong>Outstanding: ${{ number_format($project->outstanding_fees, 2) }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMeasurementModal">
                            Add Measurement
                        </button>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            Upload Document
                        </button>
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#addCommunicationModal">
                            Log Communication
                        </button>
                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                            Add Fee
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('survey.documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="survey_project_id" value="{{ $project->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" name="document_type" required>
                            <option value="">Select Type</option>
                            <option value="survey_plan">Survey Plan</option>
                            <option value="topographic_map">Topographic Map</option>
                            <option value="boundary_plan">Boundary Plan</option>
                            <option value="field_notes">Field Notes</option>
                            <option value="calculations">Calculations</option>
                            <option value="photos">Photos</option>
                            <option value="certificates">Certificates</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <div class="form-text">Max file size: 10MB. Allowed: PDF, DOC, DOCX, JPG, PNG</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 25px;
}

.timeline-marker {
    position: absolute;
    left: -8px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>
@endpush
@extends('layouts.app')

@section('title', 'Survey Project - ' . $project->project_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Survey Project Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.index') }}">Survey Services</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('survey.projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active">{{ $project->project_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">{{ $project->title }}</h4>
                            <p class="text-muted mb-0">{{ $project->project_number }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-{{ $project->status_color }} fs-6 me-2">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                            <span class="badge bg-{{ $project->priority_color }} fs-6">
                                {{ ucfirst($project->priority) }} Priority
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Project Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Survey Type</label>
                                <p class="text-muted">{{ $project->surveyType->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Client</label>
                                <p class="text-muted">{{ $project->client->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Surveyor</label>
                                <p class="text-muted">{{ $project->surveyor->name ?? 'Unassigned' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Property Address</label>
                                <p class="text-muted">{{ $project->property_address }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Property Area</label>
                                <p class="text-muted">{{ $project->property_area ? number_format($project->property_area, 2) . ' m²' : 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Coordinates</label>
                                <p class="text-muted">{{ $project->property_coordinates ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($project->description)
                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <p class="text-muted">{{ $project->description }}</p>
                    </div>
                    @endif

                    @if($project->special_requirements)
                    <div class="mb-3">
                        <label class="form-label fw-medium">Special Requirements</label>
                        <p class="text-muted">{{ $project->special_requirements }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Project Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Requested Date</label>
                                <p class="text-muted">{{ $project->requested_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Scheduled Date</label>
                                <p class="text-muted">{{ $project->scheduled_date ? $project->scheduled_date->format('M d, Y') : 'Not scheduled' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Completed Date</label>
                                <p class="text-muted">{{ $project->completed_date ? $project->completed_date->format('M d, Y') : 'Not completed' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cost Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Estimated Cost</label>
                                <p class="text-muted">R {{ number_format($project->estimated_cost, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Actual Cost</label>
                                <p class="text-muted">{{ $project->actual_cost ? 'R ' . number_format($project->actual_cost, 2) : 'Not finalized' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('survey.projects.edit', $project) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit Project
                        </a>
                        <a href="{{ route('survey.projects.measurements', $project) }}" class="btn btn-outline-primary">
                            <i class="ri-ruler-line me-1"></i> Measurements ({{ $project->measurements->count() }})
                        </a>
                        <a href="{{ route('survey.projects.documents', $project) }}" class="btn btn-outline-success">
                            <i class="ri-file-text-line me-1"></i> Documents ({{ $project->documents->count() }})
                        </a>
                        <a href="{{ route('survey.projects.boundaries', $project) }}" class="btn btn-outline-info">
                            <i class="ri-map-line me-1"></i> Boundaries ({{ $project->boundaries->count() }})
                        </a>
                        <a href="{{ route('survey.projects.fees', $project) }}" class="btn btn-outline-warning">
                            <i class="ri-money-dollar-circle-line me-1"></i> Fees ({{ $project->fees->count() }})
                        </a>
                        @if($project->status === 'completed')
                        <a href="{{ route('survey.reports.create', $project) }}" class="btn btn-outline-dark">
                            <i class="ri-file-add-line me-1"></i> Generate Report
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Project Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Project Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary">{{ $project->measurements->count() }}</h4>
                                <p class="text-muted mb-0">Measurements</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success">{{ $project->documents->count() }}</h4>
                                <p class="text-muted mb-0">Documents</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-info">{{ $project->boundaries->count() }}</h4>
                                <p class="text-muted mb-0">Boundaries</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-warning">{{ $project->fees->count() }}</h4>
                                <p class="text-muted mb-0">Fees</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Project Created</h6>
                                <p class="timeline-text">{{ $project->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($project->scheduled_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Survey Scheduled</h6>
                                <p class="timeline-text">{{ $project->scheduled_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($project->completed_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Project Completed</h6>
                                <p class="timeline-text">{{ $project->completed_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -12px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    margin-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endsection
