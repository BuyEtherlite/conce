@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">New Operating License Application</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('licensing.operating.index') }}">Operating Licenses</a></li>
                        <li class="breadcrumb-item active">New Application</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Operating License Application Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('licensing.operating.applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- License Type Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">License Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category-select" onchange="filterLicenseTypes()">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}">{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">License Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="license_type_id" id="license-type-select" required onchange="showLicenseTypeInfo()">
                                        <option value="">Select License Type</option>
                                        @foreach($licenseTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    data-category="{{ $type->category }}"
                                                    data-fee="{{ $type->fee_amount }}"
                                                    data-validity="{{ $type->validity_months }}"
                                                    data-description="{{ $type->description }}"
                                                    data-requirements="{{ json_encode($type->requirements) }}"
                                                    data-documents="{{ json_encode($type->required_documents) }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="business_name" value="{{ old('business_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Business Registration Number</label>
                                    <input type="text" class="form-control" name="business_registration_number" value="{{ old('business_registration_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Operation Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="operation_type" value="{{ old('operation_type') }}" required placeholder="e.g., Restaurant, Retail Store, Manufacturing">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estimated Employees</label>
                                    <input type="number" class="form-control" name="estimated_employees" value="{{ old('estimated_employees') }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estimated Annual Revenue</label>
                                    <input type="number" class="form-control" name="estimated_annual_revenue" value="{{ old('estimated_annual_revenue') }}" min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <!-- Applicant Information -->
                        <h6 class="mb-3 text-primary">Applicant Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="applicant_email" value="{{ old('applicant_email') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="applicant_phone" value="{{ old('applicant_phone') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Business Details -->
                        <div class="mb-3">
                            <label class="form-label">Business Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="business_address" rows="3" required>{{ old('business_address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Business Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="business_description" rows="4" required placeholder="Describe the nature of your business, activities, and services...">{{ old('business_description') }}</textarea>
                        </div>

                        <!-- Document Upload -->
                        <div class="mb-3">
                            <label class="form-label">Upload Documents</label>
                            <input type="file" class="form-control" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Upload required documents (PDF, JPG, PNG). Maximum 5MB per file.</div>
                        </div>

                        <!-- License Type Information Display -->
                        <div id="license-type-info" class="alert alert-info" style="display: none;">
                            <h6>License Type Information:</h6>
                            <div id="license-type-details"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('licensing.operating.applications.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-2"></i>Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterLicenseTypes() {
    const categorySelect = document.getElementById('category-select');
    const licenseTypeSelect = document.getElementById('license-type-select');
    const selectedCategory = categorySelect.value;
    
    // Reset license type selection
    licenseTypeSelect.value = '';
    document.getElementById('license-type-info').style.display = 'none';
    
    // Show/hide options based on category
    Array.from(licenseTypeSelect.options).forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const optionCategory = option.getAttribute('data-category');
        option.style.display = selectedCategory === '' || optionCategory === selectedCategory ? 'block' : 'none';
    });
}

function showLicenseTypeInfo() {
    const select = document.getElementById('license-type-select');
    const infoDiv = document.getElementById('license-type-info');
    const detailsDiv = document.getElementById('license-type-details');
    
    if (select.value) {
        const option = select.options[select.selectedIndex];
        const fee = option.getAttribute('data-fee');
        const validity = option.getAttribute('data-validity');
        const description = option.getAttribute('data-description');
        const requirements = JSON.parse(option.getAttribute('data-requirements') || '[]');
        const documents = JSON.parse(option.getAttribute('data-documents') || '[]');
        
        let html = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fee:</strong> $${parseFloat(fee).toFixed(2)}</p>
                    <p><strong>Validity:</strong> ${validity} months</p>
                    ${description ? `<p><strong>Description:</strong> ${description}</p>` : ''}
                </div>
                <div class="col-md-6">
        `;
        
        if (requirements.length > 0) {
            html += '<p><strong>Requirements:</strong></p><ul>';
            requirements.forEach(req => {
                html += `<li>${req}</li>`;
            });
            html += '</ul>';
        }
        
        if (documents.length > 0) {
            html += '<p><strong>Required Documents:</strong></p><ul>';
            documents.forEach(doc => {
                html += `<li>${doc}</li>`;
            });
            html += '</ul>';
        }
        
        html += '</div></div>';
        
        detailsDiv.innerHTML = html;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
}
</script>
@endpush
