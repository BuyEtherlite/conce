@extends('layouts.app')

@section('title', 'Submit Planning Application')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Submit Planning Application</h1>
                    <p class="text-muted">Submit a new town planning application</p>
                </div>
                <a href="{{ route('engineering.planning.applications.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Applications
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('engineering.planning.applications.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="council_id" class="form-label">Council</label>
                            <select class="form-select" id="council_id" name="council_id" required>
                                <option value="">Select Council</option>
                                @foreach($councils as $council)
                                    <option value="{{ $council->id }}">{{ $council->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <h5 class="mb-3">Applicant Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_name" class="form-label">Applicant Name</label>
                                    <input type="text" class="form-control" id="applicant_name" name="applicant_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="applicant_email" name="applicant_email">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="applicant_phone" name="applicant_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicant_address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="applicant_address" name="applicant_address" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Property Information</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="property_address" class="form-label">Property Address</label>
                                    <input type="text" class="form-control" id="property_address" name="property_address" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="property_erf_number" class="form-label">ERF Number</label>
                                    <input type="text" class="form-control" id="property_erf_number" name="property_erf_number">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="property_size" class="form-label">Property Size (m²)</label>
                                    <input type="number" class="form-control" id="property_size" name="property_size" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="zoning_current" class="form-label">Current Zoning</label>
                                    <input type="text" class="form-control" id="zoning_current" name="zoning_current" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="zoning_proposed" class="form-label">Proposed Zoning</label>
                                    <input type="text" class="form-control" id="zoning_proposed" name="zoning_proposed">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="application_type" class="form-label">Application Type</label>
                                    <select class="form-select" id="application_type" name="application_type" required>
                                        <option value="">Select Type</option>
                                        <option value="rezoning">Rezoning</option>
                                        <option value="subdivision">Subdivision</option>
                                        <option value="building_plan">Building Plan</option>
                                        <option value="demolition">Demolition</option>
                                        <option value="change_of_use">Change of Use</option>
                                        <option value="special_consent">Special Consent</option>
                                        <option value="departure">Departure</option>
                                        <option value="extension">Extension</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="application_fee" class="form-label">Application Fee</label>
                                    <input type="number" class="form-control" id="application_fee" name="application_fee" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="proposal_description" class="form-label">Proposal Description</label>
                            <textarea class="form-control" id="proposal_description" name="proposal_description" rows="4" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
