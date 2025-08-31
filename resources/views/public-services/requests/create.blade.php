@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus fa-fw"></i> Create Service Request
        </h1>
        <div class="d-none d-lg-inline-block">
            <a href="{{ route('public-services.requests') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Requests
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('public-services.requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Customer Section -->
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="">Select Existing Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }} - {{ $customer->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- New Customer Details -->
                        <div id="new-customer-section" class="border p-3 mb-3">
                            <h6>Or Create New Customer</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">Customer Name *</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_email">Customer Email *</label>
                                        <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_phone">Customer Phone</label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_address">Customer Address</label>
                                        <input type="text" name="customer_address" id="customer_address" class="form-control" value="{{ old('customer_address') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Request Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_type_id">Service Type *</label>
                                    <select name="service_type_id" id="service_type_id" class="form-control" required>
                                        <option value="">Select Service Type</option>
                                        @foreach($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority *</label>
                                    <select name="priority" id="priority" class="form-control" required>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location_address">Location Address</label>
                                    <input type="text" name="location_address" id="location_address" class="form-control" value="{{ old('location_address') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ward_number">Ward Number</label>
                                    <input type="text" name="ward_number" id="ward_number" class="form-control" value="{{ old('ward_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="source">Source *</label>
                                    <select name="source" id="source" class="form-control" required>
                                        <option value="website">Website</option>
                                        <option value="phone">Phone</option>
                                        <option value="email">Email</option>
                                        <option value="walk_in">Walk-in</option>
                                        <option value="mobile_app">Mobile App</option>
                                        <option value="social_media">Social Media</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_emergency" id="is_emergency" class="form-check-input" value="1">
                                        <label class="form-check-label" for="is_emergency">
                                            Emergency Request
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attachments">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control-file" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <small class="form-text text-muted">Maximum file size: 10MB per file. Supported formats: JPG, PNG, PDF, DOC, DOCX</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Submit Request
                            </button>
                            <a href="{{ route('public-services.requests') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('customer_id').addEventListener('change', function() {
    const newCustomerSection = document.getElementById('new-customer-section');
    if (this.value) {
        newCustomerSection.style.display = 'none';
    } else {
        newCustomerSection.style.display = 'block';
    }
});
</script>
@endsection
