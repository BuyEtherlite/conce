@extends('layouts.app')

@section('page-title', 'Meeting Minutes Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('committee.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2"></i>Committee Dashboard
                </a>
                <a href="{{ route('committee.committees.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-users me-2"></i>Committees
                </a>
                <a href="{{ route('committee.meetings.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-calendar me-2"></i>Meetings
                </a>
                <a href="{{ route('committee.minutes.index') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-file-alt me-2"></i>Meeting Minutes
                </a>
                <a href="{{ route('committee.agendas.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-list me-2"></i>Meeting Agendas
                </a>
                <a href="{{ route('committee.resolutions.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-gavel me-2"></i>Resolutions
                </a>
                <a href="{{ route('committee.members.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-friends me-2"></i>Members
                </a>
                <a href="{{ route('committee.public.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-globe me-2"></i>Public Documents
                </a>
                <a href="{{ route('committee.archive.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-archive me-2"></i>Archive
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Meeting Minutes Details</h4>
                <div>
                    @if($minute->status === 'draft')
                        <a href="{{ route('committee.minutes.edit', $minute->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('committee.minutes.approve', $minute->id) }}" class="btn btn-success" 
                           onclick="return confirm('Are you sure you want to approve these minutes?')">
                            <i class="fas fa-check me-1"></i>Approve
                        </a>
                    @endif
                    <a href="{{ route('committee.minutes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $minute->meeting->title ?? 'Meeting Minutes' }}</h5>
                        <span class="badge badge-{{ $minute->status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($minute->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Meeting:</strong></td>
                                    <td>{{ $minute->meeting->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Meeting Date:</strong></td>
                                    <td>{{ $minute->meeting ? $minute->meeting->meeting_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Minute Type:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $minute->minute_type)) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Recorded By:</strong></td>
                                    <td>{{ $minute->recordedBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $minute->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($minute->approved_at)
                                <tr>
                                    <td><strong>Approved:</strong></td>
                                    <td>{{ $minute->approved_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Content</h6>
                        <div class="border p-3 bg-light">
                            {!! nl2br(e($minute->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
