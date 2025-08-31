@extends('layouts.admin')

@section('content')
<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Committee Management</span>
        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('committee.index') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="{{ route('committee.committees.index') }}">
                <i class="fas fa-users"></i> Committees
            </a>
            <a class="nav-link" href="{{ route('committee.meetings.index') }}">
                <i class="fas fa-calendar"></i> Meetings
            </a>
            <a class="nav-link" href="{{ route('committee.agendas.index') }}">
                <i class="fas fa-list"></i> Agendas
            </a>
            <a class="nav-link active" href="{{ route('committee.minutes.index') }}">
                <i class="fas fa-file-alt"></i> Minutes
            </a>
            <a class="nav-link" href="{{ route('committee.members.index') }}">
                <i class="fas fa-user-friends"></i> Members
            </a>
            <a class="nav-link" href="{{ route('committee.resolutions.index') }}">
                <i class="fas fa-gavel"></i> Resolutions
            </a>
            <a class="nav-link" href="{{ route('committee.public.index') }}">
                <i class="fas fa-globe"></i> Public Documents
            </a>
            <a class="nav-link" href="{{ route('committee.archive.index') }}">
                <i class="fas fa-archive"></i> Archive
            </a>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Meeting Minutes</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('committee.minutes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Minutes
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Meeting</th>
                        <th>Type</th>
                        <th>Content Preview</th>
                        <th>Recorded By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($minutes as $minute)
                    <tr>
                        <td>{{ $minute->meeting->meeting_date ?? 'N/A' }}</td>
                        <td>{{ ucfirst($minute->minute_type) }}</td>
                        <td>{{ Str::limit($minute->content, 50) }}</td>
                        <td>{{ $minute->recordedBy->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $minute->status === 'approved' ? 'success' : ($minute->status === 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($minute->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('committee.minutes.show', $minute) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('committee.minutes.edit', $minute) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('committee.minutes.destroy', $minute) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No minutes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection