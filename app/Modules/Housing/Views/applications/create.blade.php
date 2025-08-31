@extends('layouts.admin')

@section('page-title', 'New Housing Application')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏠 New Housing Application</h4>
        <a href="{{ route('housing.applications.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Applications
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form method="POST" action="{{ route('housing.applications.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">👤 Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" 
                                           id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                    @error('applicant_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_id_number" class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('applicant_id_number') is-invalid @enderror" 
                                           id="applicant_id_number" name="applicant_id_number" value="{{ old('applicant_id_number') }}" required>
                                    @error('applicant_id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('applicant_email') is-invalid @enderror" 
                                           id="applicant_email" name="applicant_email" value="{{ old('applicant_email') }}">
                                    @error('applicant_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('applicant_phone') is-invalid @enderror" 
                                           id="applicant_phone" name="applicant_phone" value="{{ old('applicant_phone') }}" required>
                                    @error('applicant_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="applicant_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('applicant_address') is-invalid @enderror" 
                                      id="applicant_address" name="applicant_address" rows="2" required>{{ old('applicant_address') }}</textarea>
                            @error('applicant_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Household Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">👨‍👩‍👧‍👦 Household Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="family_size" class="form-label">Family Size <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('family_size') is-invalid @enderror" 
                                           id="family_size" name="family_size" value="{{ old('family_size') }}" min="1" required>
                                    @error('family_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly_income" class="form-label">Monthly Income (R) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('monthly_income') is-invalid @enderror" 
                                           id="monthly_income" name="monthly_income" value="{{ old('monthly_income') }}" required>
                                    @error('monthly_income')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('employment_status') is-invalid @enderror" 
                                    id="employment_status" name="employment_status" required>
                                <option value="">Select Employment Status</option>
                                <option value="Employed" {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                <option value="Self-employed" {{ old('employment_status') == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>
                                <option value="Unemployed" {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                <option value="Pensioner" {{ old('employment_status') == 'Pensioner' ? 'selected' : '' }}>Pensioner</option>
                                <option value="Student" {{ old('employment_status') == 'Student' ? 'selected' : '' }}>Student</option>
                            </select>
                            @error('employment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Housing Preferences -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">🏡 Housing Preferences</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preferred_area" class="form-label">Preferred Area</label>
                                    <input type="text" class="form-control @error('preferred_area') is-invalid @enderror" 
                                           id="preferred_area" name="preferred_area" value="{{ old('preferred_area') }}">
                                    @error('preferred_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="housing_type_preference" class="form-label">Housing Type Preference</label>
                                    <select class="form-select @error('housing_type_preference') is-invalid @enderror" 
                                            id="housing_type_preference" name="housing_type_preference">
                                        <option value="">No Preference</option>
                                        <option value="house" {{ old('housing_type_preference') == 'house' ? 'selected' : '' }}>House</option>
                                        <option value="apartment" {{ old('housing_type_preference') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="townhouse" {{ old('housing_type_preference') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                        <option value="flat" {{ old('housing_type_preference') == 'flat' ? 'selected' : '' }}>Flat</option>
                                    </select>
                                    @error('housing_type_preference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="special_needs" class="form-label">Special Needs or Requirements</label>
                            <textarea class="form-control @error('special_needs') is-invalid @enderror" 
                                      id="special_needs" name="special_needs" rows="3" 
                                      placeholder="e.g., wheelchair accessible, medical needs, etc.">{{ old('special_needs') }}</textarea>
                            @error('special_needs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">📄 Required Documents</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="id_document" class="form-label">ID Document/Picture <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('id_document') is-invalid @enderror" 
                                   id="id_document" name="id_document" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="form-text text-muted">Upload a clear photo of your ID document (PDF, JPG, PNG - Max 2MB)</small>
                            @error('id_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proof_of_income" class="form-label">Proof of Income</label>
                            <input type="file" class="form-control @error('proof_of_income') is-invalid @enderror" 
                                   id="proof_of_income" name="proof_of_income" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Upload salary slip, bank statement, or employment letter (PDF, JPG, PNG - Max 2MB)</small>
                            @error('proof_of_income')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proof_of_residence" class="form-label">Proof of Residence</label>
                            <input type="file" class="form-control @error('proof_of_residence') is-invalid @enderror" 
                                   id="proof_of_residence" name="proof_of_residence" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Upload utility bill or rental agreement (PDF, JPG, PNG - Max 2MB)</small>
                            @error('proof_of_residence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_documents" class="form-label">Additional Supporting Documents</label>
                            <input type="file" class="form-control @error('additional_documents.*') is-invalid @enderror" 
                                   id="additional_documents" name="additional_documents[]" 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple>
                            <small class="form-text text-muted">Upload any additional supporting documents (Multiple files allowed - Max 2MB each)</small>
                            @error('additional_documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Office Assignment -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">📍 Office Assignment</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="office_id" class="form-label">Assigned Office <span class="text-danger">*</span></label>
                            <select class="form-select @error('office_id') is-invalid @enderror" 
                                    id="office_id" name="office_id" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }} - {{ $office->council->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('office_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('housing.applications.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📋 Application Guidelines</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Please ensure all information is accurate and complete:</p>
                    <ul class="list-unstyled small text-muted">
                        <li>✓ Valid ID number is required</li>
                        <li>✓ Accurate income information</li>
                        <li>✓ Complete current address</li>
                        <li>✓ Valid contact information</li>
                        <li>✓ Specify any special requirements</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">ℹ️ What Happens Next?</h6>
                </div>
                <div class="card-body">
                    <ol class="small text-muted mb-0">
                        <li>Application will be reviewed</li>
                        <li>Priority score will be calculated</li>
                        <li>You'll be added to the waiting list</li>
                        <li>We'll contact you when housing becomes available</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
