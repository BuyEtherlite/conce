@extends('layouts.admin')

@section('page-title', 'View Committee Member')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>👤 Member Details</h4>
        <div class="btn-group">
            <a href="{{ route('committee.members.edit', $member->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('committee.members.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Members
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>{{ $member->member_name }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Committee:</strong> {{ $member->committee_name }}</p>
                    <p><strong>Position:</strong> {{ $member->position }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Appointment Date:</strong> {{ $member->appointment_date ? date('M d, Y', strtotime($member->appointment_date)) : 'N/A' }}</p>
                    <p><strong>Term End Date:</strong> {{ $member->term_end_date ? date('M d, Y', strtotime($member->term_end_date)) : 'N/A' }}</p>
                </div>
            </div>
            
            @if($member->contact_information)
                <hr>
                <div class="mb-3">
                    <h6>Contact Information</h6>
                    <p>{{ $member->contact_information }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer text-muted">
            <small>Added: {{ date('M d, Y H:i', strtotime($member->created_at)) }}</small>
            @if($member->updated_at != $member->created_at)
                <small class="ms-3">Last Updated: {{ date('M d, Y H:i', strtotime($member->updated_at)) }}</small>
            @endif
        </div>
    </div>
</div>
@endsection
