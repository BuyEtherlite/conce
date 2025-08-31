@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">New License Application</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('licensing.index') }}">Licensing</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('licensing.applications.index') }}">Applications</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">License Application Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('licensing.applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Customer *</label>
                                    <select class="form-select" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_type_id" class="form-label">License Type *</label>
                                    <select class="form-select" id="license_type_id" name="license_type_id" required>
                                        <option value="">Select License Type</option>
                                        @foreach($licenseTypes as $licenseType)
                                            <option value="{{ $licenseType->id }}" {{ old('license_type_id') == $licenseType->id ? 'selected' : '' }}>
                                                {{ $licenseType->name }} - ${{ number_format($licenseType->license_fee, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('license_type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="business_name" class="form-label">Business Name *</label>
                                    <input type="text" class="form-control" id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                    @error('business_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="business_phone" class="form-label">Business Phone</label>
                                    <input type="text" class="form-control" id="business_phone" name="business_phone" value="{{ old('business_phone') }}">
                                    @error('business_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="business_address" class="form-label">Business Address *</label>
                            <textarea class="form-control" id="business_address" name="business_address" rows="3" required>{{ old('business_address') }}</textarea>
                            @error('business_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mb-3">Applicant Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_name" class="form-label">Applicant Name *</label>
                                    <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                    @error('applicant_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_id_number" class="form-label">ID Number *</label>
                                    <input type="text" class="form-control" id="applicant_id_number" name="applicant_id_number" value="{{ old('applicant_id_number') }}" required>
                                    @error('applicant_id_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_phone" class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control" id="applicant_phone" name="applicant_phone" value="{{ old('applicant_phone') }}" required>
                                    @error('applicant_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="{{ old('applicant_email') }}">
                                    @error('applicant_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="supporting_documents" class="form-label">Supporting Documents</label>
                            <input type="file" class="form-control" id="supporting_documents" name="supporting_documents[]" multiple>
                            <div class="form-text">Upload any supporting documents (PDF, DOC, JPG, PNG)</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('licensing.applications.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
