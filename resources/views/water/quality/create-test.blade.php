@extends('layouts.admin')

@section('page-title', 'New Water Quality Test')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🧪 New Water Quality Test</h4>
        <a href="{{ route('water.quality.tests') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Tests
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Test Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('water.quality.tests.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_location" class="form-label">Test Location <span class="text-danger">*</span></label>
                            <select class="form-select" id="test_location" name="test_location" required>
                                <option value="">Select Location</option>
                                <option value="main_plant">Main Treatment Plant</option>
                                <option value="distribution_a">Distribution Point A</option>
                                <option value="distribution_b">Distribution Point B</option>
                                <option value="reservoir_1">Reservoir Tank 1</option>
                                <option value="reservoir_2">Reservoir Tank 2</option>
                                <option value="pump_station_1">Pump Station 1</option>
                                <option value="pump_station_2">Pump Station 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_type" class="form-label">Test Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="test_type" name="test_type" required>
                                <option value="">Select Test Type</option>
                                <option value="chlorine">Chlorine Level</option>
                                <option value="ph">pH Level</option>
                                <option value="bacteria">Bacteria Count</option>
                                <option value="turbidity">Turbidity</option>
                                <option value="hardness">Water Hardness</option>
                                <option value="alkalinity">Alkalinity</option>
                                <option value="tds">Total Dissolved Solids</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_date" class="form-label">Test Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="test_date" name="test_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_time" class="form-label">Test Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="test_time" name="test_time" value="{{ date('H:i') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="test_result" class="form-label">Test Result <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="test_result" name="test_result" step="0.001" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="result_unit" class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-select" id="result_unit" name="result_unit" required>
                                <option value="">Select Unit</option>
                                <option value="ppm">ppm</option>
                                <option value="pH">pH</option>
                                <option value="CFU/100ml">CFU/100ml</option>
                                <option value="NTU">NTU</option>
                                <option value="mg/L">mg/L</option>
                                <option value="ppm CaCO3">ppm CaCO3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="standard_range" class="form-label">Standard Range</label>
                            <input type="text" class="form-control" id="standard_range" name="standard_range" placeholder="e.g., 0.5-2.0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tested_by" class="form-label">Tested By <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tested_by" name="tested_by" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_status" class="form-label">Test Status</label>
                            <select class="form-select" id="test_status" name="test_status">
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending Review</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="sample_method" class="form-label">Sample Collection Method</label>
                    <select class="form-select" id="sample_method" name="sample_method">
                        <option value="grab">Grab Sample</option>
                        <option value="composite">Composite Sample</option>
                        <option value="continuous">Continuous Monitoring</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes & Observations</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Any additional observations, conditions, or remarks..."></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="notify_failed" name="notify_failed" value="1">
                        <label class="form-check-label" for="notify_failed">
                            Send alert notification if test fails
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testType = document.getElementById('test_type');
    const resultUnit = document.getElementById('result_unit');
    const standardRange = document.getElementById('standard_range');

    const testStandards = {
        'chlorine': { unit: 'ppm', range: '0.5-2.0' },
        'ph': { unit: 'pH', range: '6.5-8.5' },
        'bacteria': { unit: 'CFU/100ml', range: '0' },
        'turbidity': { unit: 'NTU', range: '< 1.0' },
        'hardness': { unit: 'ppm CaCO3', range: '50-300' },
        'alkalinity': { unit: 'ppm CaCO3', range: '30-400' },
        'tds': { unit: 'mg/L', range: '< 500' }
    };

    testType.addEventListener('change', function() {
        const selectedTest = this.value;
        if (selectedTest && testStandards[selectedTest]) {
            resultUnit.value = testStandards[selectedTest].unit;
            standardRange.value = testStandards[selectedTest].range;
        }
    });
});
</script>
@endsection
