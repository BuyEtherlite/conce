@extends('layouts.admin')

@section('page-title', 'Edit Planning Application')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Planning Application: {{ $application->application_number }}</h4>
        <div>
            <a href="{{ route('planning.applications.show', $application) }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i>View Application
            </a>
            <a href="{{ route('planning.applications.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Application Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('planning.applications.update', $application) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Applicant Information -->
                        <h5 class="mb-3 text-primary">Applicant Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applicant_name" class="required">Applicant Name</label>
                                    <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" 
                                           id="applicant_name" name="applicant_name" 
                                           value="{{ old('applicant_name', $application->applicant_name) }}" required>
                                    @error('applicant_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applicant_email" class="required">Email Address</label>
                                    <input type="email" class="form-control @error('applicant_email') is-invalid @enderror" 
                                           id="applicant_email" name="applicant_email" 
                                           value="{{ old('applicant_email', $application->applicant_email) }}" required>
                                    @error('applicant_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applicant_phone" class="required">Phone Number</label>
                                    <input type="text" class="form-control @error('applicant_phone') is-invalid @enderror" 
                                           id="applicant_phone" name="applicant_phone" 
                                           value="{{ old('applicant_phone', $application->applicant_phone) }}" required>
                                    @error('applicant_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applicant_address" class="required">Applicant Address</label>
                                    <textarea class="form-control @error('applicant_address') is-invalid @enderror" 
                                              id="applicant_address" name="applicant_address" rows="3" required>{{ old('applicant_address', $application->applicant_address) }}</textarea>
                                    @error('applicant_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Property Information -->
                        <h5 class="mb-3 text-primary">Property Information</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="property_address" class="required">Property Address</label>
                                    <textarea class="form-control @error('property_address') is-invalid @enderror" 
                                              id="property_address" name="property_address" rows="3" required>{{ old('property_address', $application->property_address) }}</textarea>
                                    @error('property_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="property_erf_number">ERF Number</label>
                                    <input type="text" class="form-control @error('property_erf_number') is-invalid @enderror" 
                                           id="property_erf_number" name="property_erf_number" 
                                           value="{{ old('property_erf_number', $application->property_erf_number) }}">
                                    @error('property_erf_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="application_type" class="required">Application Type</label>
                                    <select class="form-control @error('application_type') is-invalid @enderror" 
                                            id="application_type" name="application_type" required>
                                        <option value="">Select Application Type</option>
                                        <option value="residential" {{ old('application_type', $application->application_type) == 'residential' ? 'selected' : '' }}>Residential</option>
                                        <option value="commercial" {{ old('application_type', $application->application_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="industrial" {{ old('application_type', $application->application_type) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                        <option value="mixed_use" {{ old('application_type', $application->application_type) == 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                                        <option value="other" {{ old('application_type', $application->application_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('application_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimated_cost">Estimated Cost (R)</label>
                                    <input type="number" step="0.01" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                           id="estimated_cost" name="estimated_cost" 
                                           value="{{ old('estimated_cost', $application->estimated_cost) }}">
                                    @error('estimated_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Development Details -->
                        <div class="form-group">
                            <label for="development_description" class="required">Development Description</label>
                            <textarea class="form-control @error('development_description') is-invalid @enderror" 
                                      id="development_description" name="development_description" rows="4" required>{{ old('development_description', $application->development_description) }}</textarea>
                            @error('development_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="proposed_use" class="required">Proposed Use</label>
                            <textarea class="form-control @error('proposed_use') is-invalid @enderror" 
                                      id="proposed_use" name="proposed_use" rows="3" required>{{ old('proposed_use', $application->proposed_use) }}</textarea>
                            @error('proposed_use')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Property Specifications -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="property_size">Property Size (m²)</label>
                                    <input type="number" step="0.01" class="form-control @error('property_size') is-invalid @enderror" 
                                           id="property_size" name="property_size" 
                                           value="{{ old('property_size', $application->property_size) }}">
                                    @error('property_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="building_coverage">Building Coverage (%)</label>
                                    <input type="number" step="0.01" class="form-control @error('building_coverage') is-invalid @enderror" 
                                           id="building_coverage" name="building_coverage" 
                                           value="{{ old('building_coverage', $application->building_coverage) }}">
                                    @error('building_coverage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="floor_area">Floor Area (m²)</label>
                                    <input type="number" step="0.01" class="form-control @error('floor_area') is-invalid @enderror" 
                                           id="floor_area" name="floor_area" 
                                           value="{{ old('floor_area', $application->floor_area) }}">
                                    @error('floor_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="number_of_units">Number of Units</label>
                                    <input type="number" class="form-control @error('number_of_units') is-invalid @enderror" 
                                           id="number_of_units" name="number_of_units" 
                                           value="{{ old('number_of_units', $application->number_of_units) }}">
                                    @error('number_of_units')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parking_spaces">Parking Spaces</label>
                                    <input type="number" class="form-control @error('parking_spaces') is-invalid @enderror" 
                                           id="parking_spaces" name="parking_spaces" 
                                           value="{{ old('parking_spaces', $application->parking_spaces) }}">
                                    @error('parking_spaces')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="required">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="submitted" {{ old('status', $application->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="under_review" {{ old('status', $application->status) == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                        <option value="approved" {{ old('status', $application->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="conditional_approval" {{ old('status', $application->status) == 'conditional_approval' ? 'selected' : '' }}>Conditional Approval</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="form-group">
                            <label for="comments">Additional Comments</label>
                            <textarea class="form-control @error('comments') is-invalid @enderror" 
                                      id="comments" name="comments" rows="3">{{ old('comments', $application->comments) }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- File Uploads -->
                        <h5 class="mb-3 text-primary">Documents & Plans</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="documents">Supporting Documents</label>
                                    @if($application->documents_path)
                                        <div class="mb-2">
                                            <small class="text-muted">Current: 
                                                <a href="{{ Storage::url($application->documents_path) }}" target="_blank">View Document</a>
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('documents') is-invalid @enderror" 
                                           id="documents" name="documents" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">PDF, DOC, DOCX files only (Max: 10MB)</small>
                                    @error('documents')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="site_plan">Site Plan</label>
                                    @if($application->site_plan_path)
                                        <div class="mb-2">
                                            <small class="text-muted">Current: 
                                                <a href="{{ Storage::url($application->site_plan_path) }}" target="_blank">View Site Plan</a>
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('site_plan') is-invalid @enderror" 
                                           id="site_plan" name="site_plan" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG files only (Max: 10MB)</small>
                                    @error('site_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="architectural_plans">Architectural Plans</label>
                                    @if($application->architectural_plans_path)
                                        <div class="mb-2">
                                            <small class="text-muted">Current: 
                                                <a href="{{ Storage::url($application->architectural_plans_path) }}" target="_blank">View Plans</a>
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('architectural_plans') is-invalid @enderror" 
                                           id="architectural_plans" name="architectural_plans" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG files only (Max: 10MB)</small>
                                    @error('architectural_plans')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Application
                            </button>
                            <a href="{{ route('planning.applications.show', $application) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Application Summary</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Application #:</strong></td>
                            <td>{{ $application->application_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $application->status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date Submitted:</strong></td>
                            <td>{{ $application->date_submitted->format('M d, Y') }}</td>
                        </tr>
                        @if($application->target_decision_date)
                        <tr>
                            <td><strong>Target Decision:</strong></td>
                            <td>{{ $application->target_decision_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($application->actual_decision_date)
                        <tr>
                            <td><strong>Decision Date:</strong></td>
                            <td>{{ $application->actual_decision_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required::after {
    content: " *";
    color: red;
}
</style>
@endsection
