@extends('layouts.app')

@section('page-title', 'New Vendor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>➕ New Vendor</h4>
        <a href="{{ route('finance.accounts-payable.vendors') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Vendors
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.accounts-payable.vendors.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="vendor_name" name="vendor_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_number" class="form-label">Tax Number</label>
                                    <input type="text" class="form-control" id="tax_number" name="tax_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_terms" class="form-label">Payment Terms (Days)</label>
                                    <input type="number" class="form-control" id="payment_terms" name="payment_terms" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="credit_limit" class="form-label">Credit Limit</label>
                            <input type="number" class="form-control" id="credit_limit" name="credit_limit" min="0" step="0.01">
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Create Vendor</button>
                            <a href="{{ route('finance.accounts-payable.vendors') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
