@extends('layouts.admin')

@section('title', 'Register Fiscal Device')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plus me-2"></i>Register Fiscal Device
                    </h1>
                    <p class="mb-0 text-muted">Add a new ZIMRA-certified fiscal device</p>
                </div>
                <a href="{{ route('finance.fiscalization.devices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Devices
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Device Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('finance.fiscalization.devices.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="device_name">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('device_name') is-invalid @enderror" 
                                           id="device_name" name="device_name" value="{{ old('device_name') }}"
                                           placeholder="Enter device name" required>
                                    @error('device_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}"
                                           placeholder="Enter device serial number" required>
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="manufacturer">Manufacturer <span class="text-danger">*</span></label>
                                    <select class="form-control @error('manufacturer') is-invalid @enderror" 
                                            id="manufacturer" name="manufacturer" required>
                                        <option value="">Select Manufacturer</option>
                                        <option value="ZIMRA Approved Vendor 1" {{ old('manufacturer') === 'ZIMRA Approved Vendor 1' ? 'selected' : '' }}>ZIMRA Approved Vendor 1</option>
                                        <option value="ZIMRA Approved Vendor 2" {{ old('manufacturer') === 'ZIMRA Approved Vendor 2' ? 'selected' : '' }}>ZIMRA Approved Vendor 2</option>
                                        <option value="ZIMRA Approved Vendor 3" {{ old('manufacturer') === 'ZIMRA Approved Vendor 3' ? 'selected' : '' }}>ZIMRA Approved Vendor 3</option>
                                        <option value="Other" {{ old('manufacturer') === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="model">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model') }}"
                                           placeholder="Enter device model" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="certification_number">ZIMRA Certification Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('certification_number') is-invalid @enderror" 
                                           id="certification_number" name="certification_number" value="{{ old('certification_number') }}"
                                           placeholder="Enter ZIMRA certification number" required>
                                    @error('certification_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="device_type">Device Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('device_type') is-invalid @enderror" 
                                            id="device_type" name="device_type" required>
                                        <option value="">Select Device Type</option>
                                        <option value="pos_terminal" {{ old('device_type') === 'pos_terminal' ? 'selected' : '' }}>POS Terminal</option>
                                        <option value="cashier_terminal" {{ old('device_type') === 'cashier_terminal' ? 'selected' : '' }}>Cashier Terminal</option>
                                        <option value="mobile_device" {{ old('device_type') === 'mobile_device' ? 'selected' : '' }}>Mobile Device</option>
                                    </select>
                                    @error('device_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}"
                                           placeholder="Enter device location" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Specify where this device will be physically located (e.g., "Main Office - Counter 1")
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_user_id">Assigned User</label>
                                    <select class="form-control @error('assigned_user_id') is-invalid @enderror" 
                                            id="assigned_user_id" name="assigned_user_id">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Assign a specific user who will primarily operate this device
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Additional Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firmware_version">Firmware Version</label>
                                    <input type="text" class="form-control" id="firmware_version" name="firmware_version" 
                                           value="{{ old('firmware_version') }}" placeholder="Enter firmware version">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zimra_registration_number">ZIMRA Registration Number</label>
                                    <input type="text" class="form-control" id="zimra_registration_number" 
                                           name="zimra_registration_number" value="{{ old('zimra_registration_number') }}"
                                           placeholder="Enter ZIMRA registration number">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Important Notes:
                            </h6>
                            <ul class="mb-0">
                                <li>Ensure the device is ZIMRA-certified before registration</li>
                                <li>The serial number must match the physical device exactly</li>
                                <li>Keep certification documents for audit purposes</li>
                                <li>Device will be automatically assigned a unique Device ID upon registration</li>
                            </ul>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Register Device
                            </button>
                            <a href="{{ route('finance.fiscalization.devices.index') }}" class="btn btn-secondary ms-2">
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
