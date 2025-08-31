@extends('layouts.admin')

@section('title', 'Issue Parking Permit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shield-alt text-primary"></i>
            Issue Parking Permit
        </h1>
        <a href="{{ route('parking.permits.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Permits
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('parking.permits.store') }}" method="POST">
                        @csrf
                        
                        <!-- Permit Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Permit Details</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zone_id" class="form-label">Parking Zone *</label>
                                <select class="form-control @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id" required>
                                    <option value="">Select Zone</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->zone_name }} ({{ $zone->zone_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('zone_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="permit_type" class="form-label">Permit Type *</label>
                                <select class="form-control @error('permit_type') is-invalid @enderror" id="permit_type" name="permit_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($permitTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('permit_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('permit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Vehicle Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_registration" class="form-label">Vehicle Registration *</label>
                                <input type="text" class="form-control @error('vehicle_registration') is-invalid @enderror" 
                                       id="vehicle_registration" name="vehicle_registration" value="{{ old('vehicle_registration') }}" required>
                                @error('vehicle_registration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_make" class="form-label">Vehicle Make</label>
                                <input type="text" class="form-control @error('vehicle_make') is-invalid @enderror"
                                       id="vehicle_make" name="vehicle_make" value="{{ old('vehicle_make') }}">
                                @error('vehicle_make')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                                <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror"
                                       id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}">
                                @error('vehicle_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_color" class="form-label">Vehicle Color</label>
                                <input type="text" class="form-control @error('vehicle_color') is-invalid @enderror"
                                       id="vehicle_color" name="vehicle_color" value="{{ old('vehicle_color') }}">
                                @error('vehicle_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Holder Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Permit Holder Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="holder_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('holder_name') is-invalid @enderror" 
                                       id="holder_name" name="holder_name" value="{{ old('holder_name') }}" required>
                                @error('holder_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="holder_phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control @error('holder_phone') is-invalid @enderror"
                                       id="holder_phone" name="holder_phone" value="{{ old('holder_phone') }}" required>
                                @error('holder_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="holder_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('holder_email') is-invalid @enderror"
                                       id="holder_email" name="holder_email" value="{{ old('holder_email') }}">
                                @error('holder_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="holder_address" class="form-label">Address *</label>
                                <textarea class="form-control @error('holder_address') is-invalid @enderror" 
                                          id="holder_address" name="holder_address" rows="3" required>{{ old('holder_address') }}</textarea>
                                @error('holder_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Permit Validity -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Permit Validity</h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="issue_date" class="form-label">Issue Date *</label>
                                <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                       id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date *</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                       id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fee_amount" class="form-label">Fee Amount (R) *</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('fee_amount') is-invalid @enderror"
                                       id="fee_amount" name="fee_amount" value="{{ old('fee_amount') }}" required>
                                @error('fee_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Additional Information</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shield-alt"></i> Issue Permit
                                </button>
                                <a href="{{ route('parking.permits.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Permit Types</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Residential:</strong></td>
                                    <td>For residents</td>
                                </tr>
                                <tr>
                                    <td><strong>Business:</strong></td>
                                    <td>For business owners</td>
                                </tr>
                                <tr>
                                    <td><strong>Visitor:</strong></td>
                                    <td>For visitors</td>
                                </tr>
                                <tr>
                                    <td><strong>Disabled:</strong></td>
                                    <td>For disabled persons</td>
                                </tr>
                                <tr>
                                    <td><strong>Temporary:</strong></td>
                                    <td>Short-term permits</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
