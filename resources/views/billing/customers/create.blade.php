@extends('layouts.admin')

@section('page-title', 'Create Customer Account')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Create Customer Account</h1>
    <a href="{{ route('billing.customers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('billing.customers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control" value="Auto-generated" readonly>
                        <div class="form-text">Account number will be automatically generated</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                        <select class="form-control @error('account_type') is-invalid @enderror" id="account_type" name="account_type" required>
                            <option value="">Select Account Type</option>
                            <option value="individual" {{ old('account_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="business" {{ old('account_type') == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="organization" {{ old('account_type') == 'organization' ? 'selected' : '' }}>Organization</option>
                        </select>
                        @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="council_id" class="form-label">Council <span class="text-danger">*</span></label>
                        <select class="form-control @error('council_id') is-invalid @enderror" id="council_id" name="council_id" required>
                            <option value="">Select Council</option>
                            @foreach($councils as $council)
                                <option value="{{ $council->id }}" {{ old('council_id') == $council->id ? 'selected' : '' }}>
                                    {{ $council->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('council_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                               id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                               id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="physical_address" class="form-label">Physical Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('physical_address') is-invalid @enderror"
                                  id="physical_address" name="physical_address" rows="3" required>{{ old('physical_address') }}</textarea>
                        @error('physical_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="postal_address" class="form-label">Postal Address</label>
                        <textarea class="form-control @error('postal_address') is-invalid @enderror"
                                  id="postal_address" name="postal_address" rows="3">{{ old('postal_address') }}</textarea>
                        @error('postal_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                               id="id_number" name="id_number" value="{{ old('id_number') }}">
                        @error('id_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="vat_number" class="form-label">VAT Number</label>
                        <input type="text" class="form-control @error('vat_number') is-invalid @enderror"
                               id="vat_number" name="vat_number" value="{{ old('vat_number') }}">
                        @error('vat_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="credit_limit" class="form-label">Credit Limit</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('credit_limit') is-invalid @enderror"
                               id="credit_limit" name="credit_limit" value="{{ old('credit_limit') }}">
                        @error('credit_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Customer
                </button>
                <a href="{{ route('billing.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection