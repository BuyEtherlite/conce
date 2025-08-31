@extends('layouts.app')

@section('page-title', 'New Bill')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>➕ New Bill</h4>
        <a href="{{ route('finance.accounts-payable.bills') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Bills
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bill Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.accounts-payable.bills.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        <option value="1">ABC Suppliers Ltd</option>
                                        <option value="2">XYZ Services Inc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bill_number" class="form-label">Bill Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bill_number" name="bill_number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bill_date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="bill_date" name="bill_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Create Bill</button>
                            <a href="{{ route('finance.accounts-payable.bills') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
