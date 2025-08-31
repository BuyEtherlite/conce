@extends('layouts.admin')

@section('page-title', 'New Planning Application')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 New Planning Application</h4>
        <a href="{{ route('planning.applications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Applications
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Application Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('planning.applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Applicant Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">👤 Applicant Information</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicant_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" 
                                       id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                @error('applicant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicant_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('applicant_email') is-invalid @enderror" 
                                       id="applicant_email" name="applicant_email" value="{{ old('applicant_email') }}" required>
                                @error('applicant_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicant_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('applicant_phone') is-invalid @enderror" 
                                       id="applicant_phone" name="applicant_phone" value="{{ old('applicant_phone') }}" required>
                                @error('applicant_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicant_address" class="form-label">Postal Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('applicant_address') is-invalid @enderror" 
                                          id="applicant_address" name="applicant_address" rows="3" required>{{ old('applicant_address') }}</textarea>
                                @error('applicant_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Property Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">🏠 Property Information</h6>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="property_address" class="form-label">Property Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('property_address') is-invalid @enderror" 
                                          id="property_address" name="property_address" rows="2" required>{{ old('property_address') }}</textarea>
                                @error('property_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="property_erf_number" class="form-label">ERF Number</label>
                                <input type="text" class="form-control @error('property_erf_number') is-invalid @enderror" 
                                       id="property_erf_number" name="property_erf_number" value="{{ old('property_erf_number') }}">
                                @error('property_erf_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="application_type" class="form-label">Application Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('application_type') is-invalid @enderror" 
                                        id="application_type" name="application_type" required>
                                    <option value="">Select Application Type</option>
                                    <option value="residential" {{ old('application_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('application_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('application_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                    <option value="mixed_use" {{ old('application_type') == 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                                    <option value="other" {{ old('application_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('application_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="property_size" class="form-label">Property Size (m²)</label>
                                <input type="number" step="0.01" class="form-control @error('property_size') is-invalid @enderror" 
                                       id="property_size" name="property_size" value="{{ old('property_size') }}">
                                @error('property_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Development Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">🏗️ Development Information</h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="development_description" class="form-label">Development Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('development_description') is-invalid @enderror" 
                                          id="development_description" name="development_description" rows="3" required>{{ old('development_description') }}</textarea>
                                @error('development_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="proposed_use" class="form-label">Proposed Use <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('proposed_use') is-invalid @enderror" 
                                          id="proposed_use" name="proposed_use" rows="2" required>{{ old('proposed_use') }}</textarea>
                                @error('proposed_use')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="building_coverage" class="form-label">Building Coverage (%)</label>
                                <input type="number" step="0.01" max="100" class="form-control @error('building_coverage') is-invalid @enderror" 
                                       id="building_coverage" name="building_coverage" value="{{ old('building_coverage') }}">
                                @error('building_coverage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="floor_area" class="form-label">Floor Area (m²)</label>
                                <input type="number" step="0.01" class="form-control @error('floor_area') is-invalid @enderror" 
                                       id="floor_area" name="floor_area" value="{{ old('floor_area') }}">
                                @error('floor_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="number_of_units" class="form-label">Number of Units</label>
                                <input type="number" min="0" class="form-control @error('number_of_units') is-invalid @enderror" 
                                       id="number_of_units" name="number_of_units" value="{{ old('number_of_units') }}">
                                @error('number_of_units')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="parking_spaces" class="form-label">Parking Spaces</label>
                                <input type="number" min="0" class="form-control @error('parking_spaces') is-invalid @enderror" 
                                       id="parking_spaces" name="parking_spaces" value="{{ old('parking_spaces') }}">
                                @error('parking_spaces')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Document Uploads -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">📁 Document Uploads</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="documents" class="form-label">Supporting Documents</label>
                                <input type="file" class="form-control @error('documents') is-invalid @enderror" 
                                       id="documents" name="documents" accept=".pdf,.doc,.docx">
                                <small class="text-muted">PDF, DOC, DOCX (max 10MB)</small>
                                @error('documents')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="site_plan" class="form-label">Site Plan</label>
                                <input type="file" class="form-control @error('site_plan') is-invalid @enderror" 
                                       id="site_plan" name="site_plan" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (max 10MB)</small>
                                @error('site_plan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="architectural_plans" class="form-label">Architectural Plans</label>
                                <input type="file" class="form-control @error('architectural_plans') is-invalid @enderror" 
                                       id="architectural_plans" name="architectural_plans" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (max 10MB)</small>
                                @error('architectural_plans')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Comments -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="comments" class="form-label">Additional Comments</label>
                                <textarea class="form-control @error('comments') is-invalid @enderror" 
                                          id="comments" name="comments" rows="3">{{ old('comments') }}</textarea>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('planning.applications.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📋 Application Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6>Required Information:</h6>
                    <ul class="list-unstyled small">
                        <li>✓ Complete applicant details</li>
                        <li>✓ Accurate property information</li>
                        <li>✓ Detailed development description</li>
                        <li>✓ Proposed use of the property</li>
                    </ul>

                    <h6 class="mt-4">Required Documents:</h6>
                    <ul class="list-unstyled small">
                        <li>• Site plan (recommended)</li>
                        <li>• Architectural plans (if applicable)</li>
                        <li>• Supporting documentation</li>
                        <li>• Environmental impact assessment (if required)</li>
                    </ul>

                    <div class="alert alert-info mt-4">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Applications are typically reviewed within 30 working days. You will receive email updates on the status of your application.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
