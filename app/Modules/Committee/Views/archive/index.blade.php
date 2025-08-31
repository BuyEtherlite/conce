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
            <a class="nav-link" href="{{ route('committee.members.index') }}">
                <i class="fas fa-user-friends"></i> Members
            </a>
            <a class="nav-link" href="{{ route('committee.resolutions.index') }}">
                <i class="fas fa-gavel"></i> Resolutions
            </a>
            <a class="nav-link" href="{{ route('committee.public.index') }}">
                <i class="fas fa-globe"></i> Public Documents
            </a>
            <a class="nav-link active" href="{{ route('committee.archive.index') }}">
                <i class="fas fa-archive"></i> Archive
            </a>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Committee Archive</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Archived Committees</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-folder text-muted me-2"></i>
                                Former Planning Committee (2020-2022)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-folder text-muted me-2"></i>
                                Old Finance Committee (2019-2021)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Historical Documents</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-file-alt text-muted me-2"></i>
                                Annual Reports (2020-2023)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-file-alt text-muted me-2"></i>
                                Meeting Minutes Archive
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Legacy Resolutions</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-gavel text-muted me-2"></i>
                                Historical Decisions (2018-2023)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-gavel text-muted me-2"></i>
                                Policy Changes Archive
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Archive Search Results</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Committee</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No archived documents found. Use the search and filter options above.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection