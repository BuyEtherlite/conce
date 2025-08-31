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
            <a class="nav-link" href="{{ route('committee.minutes.index') }}">
                <i class="fas fa-file-alt"></i> Minutes
            </a>
            <a class="nav-link active" href="{{ route('committee.members.index') }}">
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
            <h1 class="h2">Committee Members</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('committee.members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Member
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

        @if($members->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Committee</th>
                            <th>Role</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->committee->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $member->role === 'chairperson' ? 'primary' : ($member->role === 'secretary' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="badge badge-{{ $member->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('committee.members.show', $member) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('committee.members.edit', $member) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('committee.members.destroy', $member) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users display-1 text-muted"></i>
                <h5 class="mt-3">No Members Found</h5>
                <p class="text-muted">There are no committee members registered yet.</p>
                <a href="{{ route('committee.members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Member
                </a>
            </div>
        @endif
    </div>
@endsection