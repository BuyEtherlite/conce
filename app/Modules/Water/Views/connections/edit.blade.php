@extends('layouts.admin')

@section('page-title', 'Edit Water Connection')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Edit Water Connection - WC-{{ str_pad($id, 3, '0', STR_PAD_LEFT) }}</h4>
        <a href="{{ route('water.connections.show', $id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Details
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Connection Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('water.connections.update', $id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="John Smith" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="connection_type" class="form-label">Connection Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="connection_type" name="connection_type" required>
                                <option value="residential" selected>Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="industrial">Industrial</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="property_address" class="form-label">Property Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="property_address" name="property_address" rows="3" required>123 Main Street, Downtown Area</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meter_number" class="form-label">Meter Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="meter_number" name="meter_number" value="M-001234" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="connection_date" class="form-label">Connection Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="connection_date" name="connection_date" value="2024-01-15" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="+1 234 567 8900">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="john.smith@email.com">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Connection Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" selected>Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="disconnected">Disconnected</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Connection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
