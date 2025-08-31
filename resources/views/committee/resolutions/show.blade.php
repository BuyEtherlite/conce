@extends('layouts.admin')

@section('page-title', 'View Resolution')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>⚖️ Resolution Details</h4>
        <div class="btn-group">
            <a href="{{ route('committee.resolutions.edit', $resolution->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('committee.resolutions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Resolutions
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>{{ $resolution->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Committee:</strong> {{ $resolution->committee->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $resolution->status == 'approved' ? 'success' : ($resolution->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($resolution->status) }}
                        </span>
                    </p>
                    <p><strong>Resolution Date:</strong> {{ $resolution->resolution_date ? $resolution->resolution_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Proposed By:</strong> {{ $resolution->proposed_by }}</p>
                    <p><strong>Seconded By:</strong> {{ $resolution->seconded_by ?? 'N/A' }}</p>
                    @if($resolution->voting_result)
                        <p><strong>Voting Result:</strong> {{ $resolution->voting_result }}</p>
                    @endif
                </div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <h6>Description</h6>
                <p>{{ $resolution->description }}</p>
            </div>
            
            @if($resolution->resolution_text)
                <div class="mb-3">
                    <h6>Resolution Text</h6>
                    <div class="border p-3 bg-light">
                        {!! nl2br(e($resolution->resolution_text)) !!}
                    </div>
                </div>
            @endif
            
            @if($resolution->votes_for || $resolution->votes_against || $resolution->abstentions)
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-success">{{ $resolution->votes_for ?? 0 }}</h4>
                            <small>Votes For</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-danger">{{ $resolution->votes_against ?? 0 }}</h4>
                            <small>Votes Against</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $resolution->abstentions ?? 0 }}</h4>
                            <small>Abstentions</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-muted">
            <small>Created: {{ $resolution->created_at->format('M d, Y H:i') }}</small>
            @if($resolution->updated_at != $resolution->created_at)
                <small class="ms-3">Last Updated: {{ $resolution->updated_at->format('M d, Y H:i') }}</small>
            @endif
        </div>
    </div>
</div>
@endsection
