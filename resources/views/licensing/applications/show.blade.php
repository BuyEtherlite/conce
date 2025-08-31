@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">License Application Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('licensing.index') }}">Licensing</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('licensing.applications.index') }}">Applications</a></li>
                        <li class="breadcrumb-item active">{{ $application->application_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Application Information</h5>
                    <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'approved' ? 'success' : 'danger') }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                            <p><strong>Business Name:</strong> {{ $application->business_name }}</p>
                            <p><strong>License Type:</strong> {{ $application->licenseType->name ?? 'N/A' }}</p>
                            <p><strong>Application Date:</strong> {{ $application->application_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Customer:</strong> {{ $application->customer->first_name ?? '' }} {{ $application->customer->last_name ?? '' }}</p>
                            <p><strong>Applicant:</strong> {{ $application->applicant_name }}</p>
                            <p><strong>Phone:</strong> {{ $application->applicant_phone }}</p>
                            <p><strong>Email:</strong> {{ $application->applicant_email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Business Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Business Address:</strong><br>{{ $application->business_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Business Phone:</strong> {{ $application->business_phone ?? 'N/A' }}</p>
                            <p><strong>ID Number:</strong> {{ $application->applicant_id_number }}</p>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Fee Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Application Fee:</strong> ${{ number_format($application->application_fee, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>License Fee:</strong> ${{ number_format($application->license_fee, 2) }}</p>
                        </div>
                    </div>

                    @if($application->review_notes)
                        <h6 class="mt-4 mb-3">Review Notes</h6>
                        <div class="alert alert-info">
                            {{ $application->review_notes }}
                        </div>
                        @if($application->review_date)
                            <p><small class="text-muted">Reviewed on {{ $application->review_date->format('M d, Y') }}</small></p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($application->status === 'pending' || $application->status === 'under_review')
                        <form action="{{ route('licensing.applications.review', $application) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="review_notes" class="form-label">Review Notes</label>
                                <textarea class="form-control" id="review_notes" name="review_notes" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="action" class="form-label">Action</label>
                                <select class="form-select" id="action" name="action" required>
                                    <option value="">Select Action</option>
                                    <option value="approve">Approve</option>
                                    <option value="reject">Reject</option>
                                    <option value="require_inspection">Require Inspection</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                        </form>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('licensing.applications.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Applications
                        </a>

                        @if($application->status === 'approved')
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-check me-2"></i>License Issued
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @if(!empty($application->supporting_documents))
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Supporting Documents</h6>
                    </div>
                    <div class="card-body">
                        @foreach($application->supporting_documents as $doc)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $doc['name'] ?? 'Document' }}</span>
                                <a href="{{ route('licensing.documents.download', $doc['id'] ?? '') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection