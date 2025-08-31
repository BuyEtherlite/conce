@extends('layouts.admin')

@section('title', 'Create Customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>👤 Create Customer</h1>
                <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Customers
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('finance.accounts-receivable.customers.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_name">Customer Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}">
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_type">Customer Type *</label>
                                    <select class="form-control" id="customer_type" name="customer_type" required>
                                        <option value="">Select Type</option>
                                        <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option value="business" {{ old('customer_type') == 'business' ? 'selected' : '' }}>Business</option>
                                        <option value="government" {{ old('customer_type') == 'government' ? 'selected' : '' }}>Government</option>
                                    </select>
                                    @error('customer_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="billing_address">Billing Address *</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="physical_address">Physical Address</label>
                            <textarea class="form-control" id="physical_address" name="physical_address" rows="3">{{ old('physical_address') }}</textarea>
                            @error('physical_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tax_number">Tax Number</label>
                                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="{{ old('tax_number') }}">
                                    @error('tax_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                    @error('contact_person')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="credit_limit">Credit Limit *</label>
                                    <input type="number" class="form-control" id="credit_limit" name="credit_limit" step="0.01" min="0" required value="{{ old('credit_limit', 0) }}">
                                    @error('credit_limit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_terms_days">Payment Terms (Days) *</label>
                                    <input type="number" class="form-control" id="payment_terms_days" name="payment_terms_days" min="1" max="365" required value="{{ old('payment_terms_days', 30) }}">
                                    @error('payment_terms_days')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Customer
                            </button>
                            <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
