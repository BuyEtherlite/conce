@extends('layouts.admin')

@section('title', 'Issue Parking Violation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-warning"></i>
            Issue Parking Violation
        </h1>
        <a href="{{ route('parking.violations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Violations
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('parking.violations.store') }}" method="POST">
                        @csrf
                        
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

                        <!-- Violation Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Violation Details</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zone_id" class="form-label">Parking Zone *</label>
                                <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id" required>
                                    <option value="">Select Zone</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->zone_code }} - {{ $zone->zone_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('zone_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="space_id" class="form-label">Parking Space</label>
                                <select class="form-select @error('space_id') is-invalid @enderror" id="space_id" name="space_id">
                                    <option value="">Select Space (Optional)</option>
                                </select>
                                @error('space_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="violation_type" class="form-label">Violation Type *</label>
                                <select class="form-select @error('violation_type') is-invalid @enderror" id="violation_type" name="violation_type" required>
                                    <option value="">Select Violation Type</option>
                                    @foreach($violationTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('violation_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('violation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fine_amount" class="form-label">Fine Amount (R) *</label>
                                <input type="number" step="0.01" class="form-control @error('fine_amount') is-invalid @enderror" 
                                       id="fine_amount" name="fine_amount" value="{{ old('fine_amount', '200.00') }}" required>
                                @error('fine_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="violation_description" class="form-label">Violation Description *</label>
                                <textarea class="form-control @error('violation_description') is-invalid @enderror" 
                                          id="violation_description" name="violation_description" rows="3" required>{{ old('violation_description') }}</textarea>
                                @error('violation_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location & Time -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Location & Time</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="violation_datetime" class="form-label">Violation Date & Time *</label>
                                <input type="datetime-local" class="form-control @error('violation_datetime') is-invalid @enderror" 
                                       id="violation_datetime" name="violation_datetime" 
                                       value="{{ old('violation_datetime', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('violation_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_address" class="form-label">Address *</label>
                                <input type="text" class="form-control @error('location.address') is-invalid @enderror" 
                                       id="location_address" name="location[address]" value="{{ old('location.address') }}" required>
                                @error('location.address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control @error('location.latitude') is-invalid @enderror" 
                                       id="location_latitude" name="location[latitude]" value="{{ old('location.latitude') }}">
                                @error('location.latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control @error('location.longitude') is-invalid @enderror" 
                                       id="location_longitude" name="location[longitude]" value="{{ old('location.longitude') }}">
                                @error('location.longitude')
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
                                <label for="notes" class="form-label">Officer Notes</label>
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
                                    <i class="fas fa-save"></i> Issue Violation
                                </button>
                                <a href="{{ route('parking.violations.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Standard Fines</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Overtime Parking</td>
                                    <td class="text-right">R150.00</td>
                                </tr>
                                <tr>
                                    <td>No Permit</td>
                                    <td class="text-right">R200.00</td>
                                </tr>
                                <tr>
                                    <td>Disabled Space</td>
                                    <td class="text-right">R500.00</td>
                                </tr>
                                <tr>
                                    <td>Loading Zone</td>
                                    <td class="text-right">R300.00</td>
                                </tr>
                                <tr>
                                    <td>Fire Hydrant</td>
                                    <td class="text-right">R750.00</td>
                                </tr>
                                <tr>
                                    <td>Double Parking</td>
                                    <td class="text-right">R400.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const zoneSelect = document.getElementById('zone_id');
    const spaceSelect = document.getElementById('space_id');
    const violationTypeSelect = document.getElementById('violation_type');
    const fineAmountInput = document.getElementById('fine_amount');
    
    const zones = @json($zones);
    const standardFines = {
        'overtime_parking': 150.00,
        'no_permit': 200.00,
        'invalid_permit': 200.00,
        'disabled_space': 500.00,
        'loading_zone': 300.00,
        'fire_hydrant': 750.00,
        'no_parking_zone': 200.00,
        'expired_meter': 150.00,
        'blocking_driveway': 300.00,
        'double_parking': 400.00,
        'wrong_direction': 200.00
    };
    
    // Update spaces when zone changes
    zoneSelect.addEventListener('change', function() {
        const zoneId = parseInt(this.value);
        spaceSelect.innerHTML = '<option value="">Select Space (Optional)</option>';
        
        if (zoneId) {
            const zone = zones.find(z => z.id === zoneId);
            if (zone && zone.spaces) {
                zone.spaces.forEach(space => {
                    const option = document.createElement('option');
                    option.value = space.id;
                    option.textContent = space.space_number + ' (' + space.space_type + ')';
                    spaceSelect.appendChild(option);
                });
            }
        }
    });
    
    // Update fine amount when violation type changes
    violationTypeSelect.addEventListener('change', function() {
        const violationType = this.value;
        if (standardFines[violationType]) {
            fineAmountInput.value = standardFines[violationType].toFixed(2);
        }
    });
});
</script>
@endsection
