@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-store me-2"></i>New Shop Permit Application
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('licensing.shop-permits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Permit Type Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Permit Type</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="shop_permit_type_id" class="form-label">Shop Permit Type <span class="text-danger">*</span></label>
                                    <select name="shop_permit_type_id" id="shop_permit_type_id" class="form-select @error('shop_permit_type_id') is-invalid @enderror" required>
                                        <option value="">Select Permit Type</option>
                                        @foreach($permitTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    data-fee="{{ $type->base_fee }}"
                                                    data-documents="{{ json_encode($type->required_documents) }}"
                                                    data-requirements="{{ json_encode($type->requirements) }}"
                                                    {{ old('shop_permit_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} - R{{ number_format($type->base_fee, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shop_permit_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div id="permit-info" class="alert alert-info d-none">
                                    <h6>Permit Information:</h6>
                                    <div id="permit-description"></div>
                                    <div id="permit-fee"></div>
                                    <div id="permit-documents"></div>
                                    <div id="permit-requirements"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Business Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                    <input type="text" name="business_name" id="business_name" 
                                           class="form-control @error('business_name') is-invalid @enderror"
                                           value="{{ old('business_name') }}" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
                                    <input type="text" name="business_type" id="business_type" 
                                           class="form-control @error('business_type') is-invalid @enderror"
                                           value="{{ old('business_type') }}" required>
                                    @error('business_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="business_address" class="form-label">Business Address <span class="text-danger">*</span></label>
                                    <textarea name="business_address" id="business_address" rows="3"
                                              class="form-control @error('business_address') is-invalid @enderror" required>{{ old('business_address') }}</textarea>
                                    @error('business_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="premise_size" class="form-label">Premise Size</label>
                                    <input type="text" name="premise_size" id="premise_size" 
                                           class="form-control @error('premise_size') is-invalid @enderror"
                                           value="{{ old('premise_size') }}" placeholder="e.g., 100 sqm">
                                    @error('premise_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="employee_count" class="form-label">Number of Employees</label>
                                    <input type="number" name="employee_count" id="employee_count" 
                                           class="form-control @error('employee_count') is-invalid @enderror"
                                           value="{{ old('employee_count') }}" min="0">
                                    @error('employee_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_monthly_turnover" class="form-label">Estimated Monthly Turnover (R)</label>
                                    <input type="number" name="estimated_monthly_turnover" id="estimated_monthly_turnover" 
                                           class="form-control @error('estimated_monthly_turnover') is-invalid @enderror"
                                           value="{{ old('estimated_monthly_turnover') }}" min="0" step="0.01">
                                    @error('estimated_monthly_turnover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="intended_start_date" class="form-label">Intended Start Date</label>
                                    <input type="date" name="intended_start_date" id="intended_start_date" 
                                           class="form-control @error('intended_start_date') is-invalid @enderror"
                                           value="{{ old('intended_start_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('intended_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Owner Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="owner_name" class="form-label">Owner Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_name" id="owner_name" 
                                           class="form-control @error('owner_name') is-invalid @enderror"
                                           value="{{ old('owner_name') }}" required>
                                    @error('owner_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="owner_id_number" class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_id_number" id="owner_id_number" 
                                           class="form-control @error('owner_id_number') is-invalid @enderror"
                                           value="{{ old('owner_id_number') }}" required>
                                    @error('owner_id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="contact_phone" id="contact_phone" 
                                           class="form-control @error('contact_phone') is-invalid @enderror"
                                           value="{{ old('contact_phone') }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="contact_email" id="contact_email" 
                                           class="form-control @error('contact_email') is-invalid @enderror"
                                           value="{{ old('contact_email') }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Activities -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Business Activities</h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Primary Business Activities</label>
                                    <div id="business-activities">
                                        <div class="input-group mb-2">
                                            <input type="text" name="business_activities[]" class="form-control" placeholder="Enter business activity">
                                            <button type="button" class="btn btn-outline-success" onclick="addActivity()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('licensing.shop-permits.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Application
                                    </button>
                                </div>
                            </div>
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
document.getElementById('shop_permit_type_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('permit-info');
    
    if (selectedOption.value) {
        const fee = selectedOption.dataset.fee;
        const documents = JSON.parse(selectedOption.dataset.documents || '[]');
        const requirements = JSON.parse(selectedOption.dataset.requirements || '[]');
        
        document.getElementById('permit-fee').innerHTML = '<strong>Fee:</strong> R' + parseFloat(fee).toLocaleString('en-ZA', {minimumFractionDigits: 2});
        
        if (documents.length > 0) {
            document.getElementById('permit-documents').innerHTML = '<strong>Required Documents:</strong> ' + documents.join(', ');
        }
        
        if (requirements.length > 0) {
            document.getElementById('permit-requirements').innerHTML = '<strong>Requirements:</strong> ' + requirements.join('; ');
        }
        
        infoDiv.classList.remove('d-none');
    } else {
        infoDiv.classList.add('d-none');
    }
});

function addActivity() {
    const container = document.getElementById('business-activities');
    const newActivity = document.createElement('div');
    newActivity.className = 'input-group mb-2';
    newActivity.innerHTML = `
        <input type="text" name="business_activities[]" class="form-control" placeholder="Enter business activity">
        <button type="button" class="btn btn-outline-danger" onclick="removeActivity(this)">
            <i class="fas fa-minus"></i>
        </button>
    `;
    container.appendChild(newActivity);
}

function removeActivity(button) {
    button.closest('.input-group').remove();
}
</script>
@endpush
