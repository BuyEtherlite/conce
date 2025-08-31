@extends('layouts.admin')

@section('page-title', 'Housing Application Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏠 Application #{{ $application->application_number }}</h4>
        <div>
            <a href="{{ route('housing.applications.edit', $application) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('housing.applications.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">👤 Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> {{ $application->applicant_name }}</p>
                            <p><strong>ID Number:</strong> {{ $application->applicant_id_number }}</p>
                            <p><strong>Email:</strong> {{ $application->applicant_email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> {{ $application->applicant_phone }}</p>
                            <p><strong>Family Size:</strong> {{ $application->family_size }}</p>
                            <p><strong>Monthly Income:</strong> R{{ number_format($application->monthly_income, 2) }}</p>
                        </div>
                    </div>
                    <p><strong>Current Address:</strong> {{ $application->applicant_address }}</p>
                    <p><strong>Employment Status:</strong> {{ $application->employment_status }}</p>
                </div>
            </div>

            <!-- Housing Preferences -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">🏡 Housing Preferences</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Preferred Area:</strong> {{ $application->preferred_area ?? 'No preference' }}</p>
                            <p><strong>Housing Type:</strong> {{ ucfirst($application->housing_type_preference) }}</p>
                        </div>
                    </div>
                    @if($application->special_needs)
                    <p><strong>Special Needs:</strong> {{ $application->special_needs }}</p>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">📄 Uploaded Documents</h6>
                </div>
                <div class="card-body">
                    @if($application->id_document_path)
                    <div class="mb-3">
                        <strong>ID Document:</strong>
                        <a href="{{ asset('storage/' . $application->id_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-download me-1"></i>View Document
                        </a>
                    </div>
                    @endif

                    @if($application->proof_of_income_path)
                    <div class="mb-3">
                        <strong>Proof of Income:</strong>
                        <a href="{{ asset('storage/' . $application->proof_of_income_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-download me-1"></i>View Document
                        </a>
                    </div>
                    @endif

                    @if($application->proof_of_residence_path)
                    <div class="mb-3">
                        <strong>Proof of Residence:</strong>
                        <a href="{{ asset('storage/' . $application->proof_of_residence_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-download me-1"></i>View Document
                        </a>
                    </div>
                    @endif

                    @if($application->additional_documents && count($application->additional_documents) > 0)
                    <div class="mb-3">
                        <strong>Additional Documents:</strong>
                        <ul class="list-unstyled mt-2">
                            @foreach($application->additional_documents as $doc)
                            <li class="mb-2">
                                <i class="fas fa-file-alt me-2"></i>{{ $doc['original_name'] }}
                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                                <small class="text-muted">({{ number_format($doc['size'] / 1024, 2) }} KB)</small>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(!$application->id_document_path && !$application->proof_of_income_path && !$application->proof_of_residence_path && (!$application->additional_documents || count($application->additional_documents) == 0))
                    <p class="text-muted">No documents uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Application Status -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">📊 Application Status</h6>
                </div>
                <div class="card-body">
                    <span class="badge bg-{{ $application->status_badge }} fs-6">
                        {{ ucfirst($application->status) }}
                    </span>
                    <p class="mt-3 mb-1"><strong>Priority Score:</strong> {{ $application->priority_score }}</p>
                    <p class="mb-1"><strong>Applied Date:</strong> {{ $application->application_date->format('M d, Y') }}</p>
                    <p class="mb-0"><strong>Application Number:</strong> {{ $application->application_number }}</p>
                </div>
            </div>

            <!-- Office Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">🏢 Assigned Office</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Office:</strong> {{ $application->office->name ?? 'N/A' }}</p>
                    @if($application->office && $application->office->council)
                    <p class="mb-0"><strong>Council:</strong> {{ $application->office->council->name }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
