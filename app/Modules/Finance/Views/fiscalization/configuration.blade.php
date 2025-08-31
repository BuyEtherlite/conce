@extends('layouts.admin')

@section('title', 'Fiscal Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-cog me-2"></i>Fiscal Configuration
                    </h1>
                    <p class="mb-0 text-muted">Configure ZIMRA fiscalization settings</p>
                </div>
                <a href="{{ route('finance.fiscalization.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Fiscalization
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building me-2"></i>Company & Tax Information
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('finance.fiscalization.update-configuration') }}">
                        @csrf
                        @method('PUT')

                        <!-- Enable/Disable Fiscalization -->
                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_fiscalization_enabled" 
                                       name="is_fiscalization_enabled" value="1" 
                                       {{ $config->is_fiscalization_enabled ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_fiscalization_enabled">
                                    <strong>Enable ZIMRA Fiscalization</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                When enabled, all financial transactions will be processed through ZIMRA fiscal devices.
                            </small>
                        </div>

                        <div id="fiscal-details" style="{{ $config->is_fiscalization_enabled ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_tin">Company TIN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('company_tin') is-invalid @enderror" 
                                               id="company_tin" name="company_tin" value="{{ old('company_tin', $config->company_tin) }}"
                                               placeholder="Enter company TIN">
                                        @error('company_tin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_office_code">Tax Office Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tax_office_code') is-invalid @enderror" 
                                               id="tax_office_code" name="tax_office_code" value="{{ old('tax_office_code', $config->tax_office_code) }}"
                                               placeholder="Enter tax office code">
                                        @error('tax_office_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_name">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" name="company_name" value="{{ old('company_name', $config->company_name) }}"
                                   placeholder="Enter company name" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="company_address">Company Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                      id="company_address" name="company_address" rows="3" 
                                      placeholder="Enter complete company address" required>{{ old('company_address', $config->company_address) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_license_number">Business License Number</label>
                                    <input type="text" class="form-control @error('business_license_number') is-invalid @enderror" 
                                           id="business_license_number" name="business_license_number" 
                                           value="{{ old('business_license_number', $config->business_license_number) }}"
                                           placeholder="Enter business license number">
                                    @error('business_license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vat_registration_number">VAT Registration Number</label>
                                    <input type="text" class="form-control @error('vat_registration_number') is-invalid @enderror" 
                                           id="vat_registration_number" name="vat_registration_number" 
                                           value="{{ old('vat_registration_number', $config->vat_registration_number) }}"
                                           placeholder="Enter VAT registration number">
                                    @error('vat_registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fiscal_year_start">Fiscal Year Start <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fiscal_year_start') is-invalid @enderror" 
                                           id="fiscal_year_start" name="fiscal_year_start" 
                                           value="{{ old('fiscal_year_start', $config->fiscal_year_start->format('Y-m-d')) }}" required>
                                    @error('fiscal_year_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="default_tax_rate">Default Tax Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="100" 
                                           class="form-control @error('default_tax_rate') is-invalid @enderror" 
                                           id="default_tax_rate" name="default_tax_rate" 
                                           value="{{ old('default_tax_rate', $config->default_tax_rate) }}" required>
                                    @error('default_tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency_code">Currency Code <span class="text-danger">*</span></label>
                                    <select class="form-control @error('currency_code') is-invalid @enderror" 
                                            id="currency_code" name="currency_code" required>
                                        <option value="USD" {{ $config->currency_code === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="ZWL" {{ $config->currency_code === 'ZWL' ? 'selected' : '' }}>ZWL - Zimbabwe Dollar</option>
                                    </select>
                                    @error('currency_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-receipt me-2"></i>Receipt Configuration
                        </h6>

                        <div class="form-group">
                            <label for="receipt_header_text">Receipt Header Text</label>
                            <textarea class="form-control" id="receipt_header_text" name="receipt_header_text" rows="3" 
                                      placeholder="Additional text to appear at the top of receipts">{{ old('receipt_header_text', $config->receipt_header_text) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="receipt_footer_text">Receipt Footer Text</label>
                            <textarea class="form-control" id="receipt_footer_text" name="receipt_footer_text" rows="3" 
                                      placeholder="Additional text to appear at the bottom of receipts">{{ old('receipt_footer_text', $config->receipt_footer_text) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="require_customer_details" 
                                           name="require_customer_details" value="1" 
                                           {{ $config->require_customer_details ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="require_customer_details">
                                        Require Customer Details on Receipts
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="auto_transmit_to_zimra" 
                                           name="auto_transmit_to_zimra" value="1" 
                                           {{ $config->auto_transmit_to_zimra ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="auto_transmit_to_zimra">
                                        Auto-transmit receipts to ZIMRA
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Configuration
                            </button>
                            <a href="{{ route('finance.fiscalization.index') }}" class="btn btn-secondary ms-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fiscalizationToggle = document.getElementById('is_fiscalization_enabled');
    const fiscalDetails = document.getElementById('fiscal-details');
    
    fiscalizationToggle.addEventListener('change', function() {
        if (this.checked) {
            fiscalDetails.style.display = 'block';
        } else {
            fiscalDetails.style.display = 'none';
        }
    });
});
</script>
@endsection
