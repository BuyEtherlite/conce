@extends('layouts.admin')

@section('page-title', 'Planning Application Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Planning Application: {{ $application->formatted_application_number }}</h4>
        <div>
            <a href="{{ route('planning.applications.edit', $application) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('planning.applications') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Application Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Application Information</h6>
                    <span class="badge bg-{{ $application->status_badge }} text-uppercase">{{ str_replace('_', ' ', $application->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Application Number:</strong><br>
                            {{ $application->formatted_application_number }}<br><br>
                            
                            <strong>Application Type:</strong><br>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $application->application_type)) }}</span><br><br>
                            
                            <strong>Date Submitted:</strong><br>
                            {{ $application->date_submitted->format('d M Y') }}<br><br>
                            
                            @if($application->date_reviewed)
                                <strong>Date Reviewed:</strong><br>
                                {{ $application->date_reviewed->format('d M Y') }}<br><br>
                                
                                <strong>Reviewed By:</strong><br>
                                {{ $application->reviewed_by }}<br><br>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($application->property_size)
                                <strong>Property Size:</strong><br>
                                {{ number_format($application->property_size, 2) }} m²<br><br>
                            @endif
                            
                            @if($application->floor_area)
                                <strong>Floor Area:</strong><br>
                                {{ number_format($application->floor_area, 2) }} m²<br><br>
                            @endif
                            
                            @if($application->building_coverage)
                                <strong>Building Coverage:</strong><br>
                                {{ $application->building_coverage }}%<br><br>
                            @endif
                            
                            @if($application->number_of_units)
                                <strong>Number of Units:</strong><br>
                                {{ $application->number_of_units }}<br><br>
                            @endif
                            
                            @if($application->parking_spaces)
                                <strong>Parking Spaces:</strong><br>
                                {{ $application->parking_spaces }}<br><br>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicant Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">👤 Applicant Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong><br>
                            {{ $application->applicant_name }}<br><br>
                            
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $application->applicant_email }}">{{ $application->applicant_email }}</a><br><br>
                            
                            <strong>Phone:</strong><br>
                            <a href="tel:{{ $application->applicant_phone }}">{{ $application->applicant_phone }}</a><br><br>
                        </div>
                        <div class="col-md-6">
                            <strong>Address:</strong><br>
                            {{ $application->applicant_address }}<br><br>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">🏠 Property Information</h6>
                </div>
                <div class="card-body">
                    <strong>Property Address:</strong><br>
                    {{ $application->property_address }}<br><br>
                    
                    @if($application->property_erf_number)
                        <strong>ERF Number:</strong><br>
                        {{ $application->property_erf_number }}<br><br>
                    @endif
                </div>
            </div>

            <!-- Development Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">🏗️ Development Information</h6>
                </div>
                <div class="card-body">
                    <strong>Development Description:</strong><br>
                    {{ $application->development_description }}<br><br>
                    
                    <strong>Proposed Use:</strong><br>
                    {{ $application->proposed_use }}<br><br>
                </div>
            </div>

            @if($application->conditions || $application->rejection_reason)
                <!-- Review Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">📋 Review Information</h6>
                    </div>
                    <div class="card-body">
                        @if($application->conditions)
                            <strong>Conditions:</strong><br>
                            {{ $application->conditions }}<br><br>
                        @endif
                        
                        @if($application->rejection_reason)
                            <strong>Rejection Reason:</strong><br>
                            <div class="alert alert-danger">
                                {{ $application->rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($application->comments)
                <!-- Comments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">💬 Comments</h6>
                    </div>
                    <div class="card-body">
                        {{ $application->comments }}
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Document Downloads -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">📁 Documents</h6>
                </div>
                <div class="card-body">
                    @if($application->documents_path)
                        <div class="mb-3">
                            <a href="{{ Storage::url($application->documents_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-file-pdf me-1"></i>Supporting Documents
                            </a>
                        </div>
                    @endif
                    
                    @if($application->site_plan_path)
                        <div class="mb-3">
                            <a href="{{ Storage::url($application->site_plan_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-map me-1"></i>Site Plan
                            </a>
                        </div>
                    @endif
                    
                    @if($application->architectural_plans_path)
                        <div class="mb-3">
                            <a href="{{ Storage::url($application->architectural_plans_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-drafting-compass me-1"></i>Architectural Plans
                            </a>
                        </div>
                    @endif
                    
                    @if(!$application->documents_path && !$application->site_plan_path && !$application->architectural_plans_path)
                        <p class="text-muted">No documents uploaded.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">⚡ Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($application->status === 'pending')
                        <form action="{{ route('planning.applications.update', $application) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="under_review">
                            <button type="submit" class="btn btn-info btn-sm w-100 mb-2">
                                <i class="fas fa-search me-1"></i>Start Review
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($application->status, ['pending', 'under_review']))
                        <button type="button" class="btn btn-success btn-sm w-100 mb-2" onclick="approveApplication()">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-sm w-100 mb-2" onclick="rejectApplication()">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                    @endif
                    
                    <a href="mailto:{{ $application->applicant_email }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-envelope me-1"></i>Contact Applicant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('planning.applications.update', $application) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="approved">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="conditions" class="form-label">Conditions (if any)</label>
                        <textarea class="form-control" id="conditions" name="conditions" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('planning.applications.update', $application) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveApplication() {
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function rejectApplication() {
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endsection
