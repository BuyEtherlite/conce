@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">New Health Permit Application</h1>
                <a href="{{ route('health.permits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Permits
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('health.permits.store') }}">
                        @csrf
                        
                        <h6 class="text-primary mb-3">Business Information</h6>
                        
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" required>
                        </div>

                        <div class="form-group">
                            <label for="business_address">Business Address</label>
                            <textarea class="form-control" id="business_address" name="business_address" rows="2" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="tel" class="form-control" id="contact_phone" name="contact_phone" required>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3 mt-4">Permit Information</h6>

                        <div class="form-group">
                            <label for="permit_type">Permit Type</label>
                            <select class="form-control" id="permit_type" name="permit_type" required>
                                <option value="">Select Permit Type</option>
                                <option value="food_service">Food Service Permit</option>
                                <option value="healthcare_facility">Healthcare Facility License</option>
                                <option value="personal_care">Personal Care Services</option>
                                <option value="child_care">Child Care Facility</option>
                                <option value="swimming_pool">Swimming Pool Permit</option>
                                <option value="waste_management">Waste Management</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="business_description">Business Description</label>
                            <textarea class="form-control" id="business_description" name="business_description" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employees_count">Number of Employees</label>
                                    <input type="number" class="form-control" id="employees_count" name="employees_count" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operating_hours">Operating Hours</label>
                                    <input type="text" class="form-control" id="operating_hours" name="operating_hours" placeholder="e.g., 9:00 AM - 6:00 PM" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="special_requirements">Special Requirements/Notes</label>
                            <textarea class="form-control" id="special_requirements" name="special_requirements" rows="2"></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                            <a href="{{ route('health.permits.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Required Documents</h6>
                </div>
                <div class="card-body">
                    <h6>General Requirements:</h6>
                    <ul class="small">
                        <li>Business registration certificate</li>
                        <li>Floor plan/layout</li>
                        <li>Owner identification</li>
                        <li>Tax clearance certificate</li>
                    </ul>

                    <h6>Food Service Additional:</h6>
                    <ul class="small">
                        <li>Food handler certificates</li>
                        <li>Equipment specifications</li>
                        <li>Water quality test</li>
                        <li>Waste disposal plan</li>
                    </ul>

                    <h6>Healthcare Facility Additional:</h6>
                    <ul class="small">
                        <li>Professional licenses</li>
                        <li>Medical equipment certificates</li>
                        <li>Infection control protocols</li>
                        <li>Emergency response plan</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Processing Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="small">
                        <li><strong>Application Review:</strong> 3-5 business days</li>
                        <li><strong>Site Inspection:</strong> 5-7 business days</li>
                        <li><strong>Permit Issuance:</strong> 2-3 business days</li>
                        <li><strong>Total Processing:</strong> 10-15 business days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
