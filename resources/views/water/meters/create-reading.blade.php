@extends('layouts.admin')

@section('page-title', 'Record Meter Reading')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📊 Record Meter Reading</h4>
        <a href="{{ route('water.meters.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Meters
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Meter Reading Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('water.meters.store-reading') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="connection_id" class="form-label">Water Connection *</label>
                                <select class="form-select" id="connection_id" name="connection_id" required>
                                    <option value="">Select Connection</option>
                                    @foreach($connections as $connection)
                                    <option value="{{ $connection->id }}" {{ old('connection_id') == $connection->id ? 'selected' : '' }}>
                                        {{ $connection->connection_number }} - {{ $connection->customer_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reading_date" class="form-label">Reading Date *</label>
                                <input type="date" class="form-control" id="reading_date" name="reading_date"
                                       value="{{ old('reading_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meter_reading" class="form-label">Meter Reading (kL) *</label>
                                <input type="number" class="form-control" id="meter_reading" name="meter_reading"
                                       step="0.1" min="0" value="{{ old('meter_reading') }}" required>
                                <div class="form-text">Enter the current reading from the meter</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Previous Reading</label>
                                <input type="text" class="form-control" id="previous_reading" readonly
                                       placeholder="Will show when connection is selected">
                                <div class="form-text">Last recorded reading for this connection</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reader_notes" class="form-label">Reader Notes</label>
                            <textarea class="form-control" id="reader_notes" name="reader_notes" rows="3"
                                      placeholder="Any observations or notes about the reading...">{{ old('reader_notes') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info" id="consumption_alert" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span id="consumption_text"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_generate_bill" name="auto_generate_bill" value="1" checked>
                                    <label class="form-check-label" for="auto_generate_bill">
                                        Automatically generate bill when consumption > 0
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Record Reading</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reading Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>How to Read Meters:</h6>
                    <ol class="small">
                        <li>Locate the water meter (usually near the street)</li>
                        <li>Remove the meter box cover carefully</li>
                        <li>Read the numbers from left to right</li>
                        <li>Record only the black numbers (ignore red)</li>
                        <li>Take a photo if needed for verification</li>
                    </ol>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <small><strong>Important:</strong> Ensure the new reading is higher than the previous reading.</small>
                    </div>

                    <div class="mt-3">
                        <h6>Reading Status:</h6>
                        <div class="small">
                            <div><span class="badge bg-success">Normal</span> 0-50 kL monthly</div>
                            <div><span class="badge bg-warning">High</span> 51-100 kL monthly</div>
                            <div><span class="badge bg-danger">Very High</span> 100+ kL monthly</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Connection Details</h5>
                </div>
                <div class="card-body" id="connection_details" style="display: none;">
                    <div class="small">
                        <div><strong>Customer:</strong> <span id="customer_name"></span></div>
                        <div><strong>Address:</strong> <span id="property_address"></span></div>
                        <div><strong>Type:</strong> <span id="connection_type"></span></div>
                        <div><strong>Meter Size:</strong> <span id="meter_size"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('connection_id').addEventListener('change', function() {
    const connectionId = this.value;
    if (connectionId) {
        // In a real application, you would fetch this data via AJAX
        // For demo purposes, we'll simulate the data
        document.getElementById('previous_reading').value = '1245.5 kL';
        document.getElementById('connection_details').style.display = 'block';
        document.getElementById('customer_name').textContent = 'John Smith';
        document.getElementById('property_address').textContent = '123 Main Street';
        document.getElementById('connection_type').textContent = 'Residential';
        document.getElementById('meter_size').textContent = '20mm';
    } else {
        document.getElementById('previous_reading').value = '';
        document.getElementById('connection_details').style.display = 'none';
    }
});

document.getElementById('meter_reading').addEventListener('input', function() {
    const currentReading = parseFloat(this.value) || 0;
    const previousReading = 1245.5; // This would come from the selected connection

    if (currentReading > 0 && currentReading > previousReading) {
        const consumption = currentReading - previousReading;
        document.getElementById('consumption_text').textContent =
            `Calculated consumption: ${consumption.toFixed(1)} kL`;
        document.getElementById('consumption_alert').style.display = 'block';

        if (consumption > 100) {
            document.getElementById('consumption_alert').className = 'alert alert-danger';
        } else if (consumption > 50) {
            document.getElementById('consumption_alert').className = 'alert alert-warning';
        } else {
            document.getElementById('consumption_alert').className = 'alert alert-info';
        }
    } else {
        document.getElementById('consumption_alert').style.display = 'none';
    }
});
</script>
@endsection