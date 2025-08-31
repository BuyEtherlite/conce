@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Schedule New Inspection</h1>
                <a href="{{ route('health.inspections.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Inspections
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('health.inspections.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="establishment_name">Establishment Name</label>
                            <input type="text" class="form-control" id="establishment_name" name="establishment_name" required>
                        </div>

                        <div class="form-group">
                            <label for="inspection_type">Inspection Type</label>
                            <select class="form-control" id="inspection_type" name="inspection_type" required>
                                <option value="">Select Type</option>
                                <option value="food_safety">Food Safety</option>
                                <option value="healthcare_facility">Healthcare Facility</option>
                                <option value="pharmaceutical">Pharmaceutical</option>
                                <option value="environmental">Environmental Health</option>
                                <option value="public_health">Public Health</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inspection_date">Inspection Date</label>
                            <input type="date" class="form-control" id="inspection_date" name="inspection_date" required>
                        </div>

                        <div class="form-group">
                            <label for="inspector">Inspector</label>
                            <select class="form-control" id="inspector" name="inspector" required>
                                <option value="">Select Inspector</option>
                                <option value="Dr. Smith">Dr. Smith</option>
                                <option value="Dr. Johnson">Dr. Johnson</option>
                                <option value="Dr. Williams">Dr. Williams</option>
                                <option value="Dr. Brown">Dr. Brown</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="routine">Routine</option>
                                <option value="complaint">Complaint Follow-up</option>
                                <option value="emergency">Emergency</option>
                                <option value="renewal">License Renewal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Schedule Inspection</button>
                            <a href="{{ route('health.inspections.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Inspection Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6>Food Safety Inspections:</h6>
                    <ul class="small">
                        <li>Temperature controls</li>
                        <li>Food handling procedures</li>
                        <li>Sanitation practices</li>
                        <li>Storage conditions</li>
                    </ul>

                    <h6>Healthcare Facility Inspections:</h6>
                    <ul class="small">
                        <li>Infection control measures</li>
                        <li>Equipment maintenance</li>
                        <li>Staff qualifications</li>
                        <li>Patient safety protocols</li>
                    </ul>

                    <h6>Environmental Health:</h6>
                    <ul class="small">
                        <li>Air quality assessment</li>
                        <li>Water quality testing</li>
                        <li>Waste management</li>
                        <li>Noise level monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
