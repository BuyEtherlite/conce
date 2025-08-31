@extends('layouts.app')

@section('page-title', 'Edit Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-edit me-2"></i>Edit Customer: {{ $customer->customer_name }}</h4>
        <div>
            <a href="{{ route('finance.accounts-receivable.customers.show', $customer) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>View Customer
            </a>
            <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Customers
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('finance.accounts-receivable.customers.update', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_type" class="form-label">Customer Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_type') is-invalid @enderror" 
                                            id="customer_type" name="customer_type" required>
                                        <option value="">Select Type</option>
                                        <option value="individual" {{ old('customer_type', $customer->account_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option value="business" {{ old('customer_type', $customer->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                        <option value="government" {{ old('customer_type', $customer->account_type) == 'government' ? 'selected' : '' }}>Government</option>
                                    </select>
                                    @error('customer_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                           id="contact_person" name="contact_person" value="{{ old('contact_person', $customer->contact_person) }}">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $customer->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_number" class="form-label">Tax Number</label>
                                    <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                                           id="tax_number" name="tax_number" value="{{ old('tax_number', $customer->id_number) }}">
                                    @error('tax_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="physical_address" class="form-label">Physical Address</label>
                            <textarea class="form-control @error('physical_address') is-invalid @enderror" 
                                      id="physical_address" name="physical_address" rows="3">{{ old('physical_address', $customer->physical_address) }}</textarea>
                            @error('physical_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('billing_address') is-invalid @enderror" 
                                      id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address', $customer->postal_address) }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="credit_limit" class="form-label">Credit Limit <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('credit_limit') is-invalid @enderror" 
                                       id="credit_limit" name="credit_limit" step="0.01" min="0" 
                                       value="{{ old('credit_limit', $customer->credit_limit) }}" required>
                                @error('credit_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
