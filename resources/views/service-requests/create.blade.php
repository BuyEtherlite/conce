@extends('layouts.app')

@section('title', 'Create Service Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Create Service Request</h1>
                <p class="page-subtitle">Submit a new service request</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Service Request Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('service-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Customer Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Customer Phone</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Service Type</label>
                                <select class="form-control" id="service_type" name="service_type_id" required>
                                    <option value="">Select Service Type</option>
                                    <option value="1">Water Connection</option>
                                    <option value="2">Road Repair</option>
                                    <option value="3">Waste Collection</option>
                                    <option value="4">Street Lighting</option>
                                    <option value="5">Building Permit</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-control" id="department" name="department_id">
                                    <option value="">Select Department</option>
                                    <option value="1">Public Works</option>
                                    <option value="2">Utilities</option>
                                    <option value="3">Health</option>
                                    <option value="4">Planning</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <textarea class="form-control" id="location" name="location" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_emergency" name="is_emergency" value="1">
                                <label class="form-check-label" for="is_emergency">
                                    This is an emergency request
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                            <small class="form-text text-muted">Upload any relevant documents or images</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Service Request Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Priority Levels:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-success">Low</span> - Non-urgent issues</li>
                        <li><span class="badge bg-warning">Medium</span> - Standard requests</li>
                        <li><span class="badge bg-orange">High</span> - Important issues</li>
                        <li><span class="badge bg-danger">Urgent</span> - Emergency situations</li>
                    </ul>

                    <h6 class="mt-3">Response Times:</h6>
                    <ul class="list-unstyled">
                        <li>Urgent: Within 2 hours</li>
                        <li>High: Within 24 hours</li>
                        <li>Medium: Within 3 days</li>
                        <li>Low: Within 7 days</li>
                    </ul>

                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> Emergency requests are automatically escalated and will receive immediate attention.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
