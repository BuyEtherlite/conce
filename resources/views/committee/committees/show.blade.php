@extends('layouts.app')

@section('page-title', 'Committee Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('committee.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2"></i>Committee Dashboard
                </a>
                <a href="{{ route('committee.committees.index') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-users me-2"></i>Committees
                </a>
                <a href="{{ route('committee.meetings.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-calendar me-2"></i>Meetings
                </a>
                <a href="{{ route('committee.minutes.index') }}" class="list-group-item list-group-item-action">
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
                <h4>Committee Details</h4>
                <div>
                    <a href="{{ route('committee.committees.edit', $committee->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('committee.committees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $committee->name }}</h5>
                            <p class="text-muted">{{ $committee->description }}</p>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Chairman:</strong></td>
                                    <td>{{ $committee->chairman_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Secretary:</strong></td>
                                    <td>{{ $committee->secretary_name ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Established:</strong></td>
                                    <td>{{ $committee->established_date ? $committee->established_date->format('M d, Y') : 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Meeting Schedule:</strong></td>
                                    <td>{{ $committee->meeting_schedule ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $committee->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($committee->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistics</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $committee->members->count() }}</h4>
                                            <small>Members</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $committee->meetings->count() }}</h4>
                                            <small>Meetings</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Meetings -->
            @if($committee->meetings->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6>Recent Meetings</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($committee->meetings->take(5) as $meeting)
                                <tr>
                                    <td>{{ $meeting->title }}</td>
                                    <td>{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                    <td>{{ $meeting->start_time }}</td>
                                    <td>{{ $meeting->location }}</td>
                                    <td>
                                        <span class="badge badge-{{ $meeting->status === 'completed' ? 'success' : ($meeting->status === 'scheduled' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($meeting->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
