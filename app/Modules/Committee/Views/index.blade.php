@extends('layouts.admin')

@section('page-title', 'Committee Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>👥 Committee Administration</h4>
        <a href="{{ route('committee.meetings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Schedule Meeting
        </a>
    </div>

    <!-- Statistics Overview -->
    <div class="card mb-4">
        <div class="card-body">
            <h6>Committee Overview</h6>
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-primary">{{ DB::table('committee_members')->where('status', 'active')->count() }}</h3>
                        <small class="text-muted">Active Members</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-warning">{{ DB::table('committee_meetings')->where('status', 'scheduled')->count() }}</h3>
                        <small class="text-muted">Scheduled Meetings</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-success">{{ DB::table('committee_meetings')->whereYear('meeting_date', date('Y'))->count() }}</h3>
                        <small class="text-muted">Meetings This Year</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-info">{{ DB::table('committee_minutes')->where('status', 'approved')->count() }}</h3>
                        <small class="text-muted">Approved Minutes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Navigation Cards -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.committees.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-sitemap fa-3x text-primary mb-3"></i>
                    <h5>Committees</h5>
                    <p class="text-muted">Manage committee structure and information</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.members.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5>Members</h5>
                    <p class="text-muted">Committee member management</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.meetings.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-calendar fa-3x text-warning mb-3"></i>
                    <h5>Meetings</h5>
                    <p class="text-muted">Meeting scheduling and management</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.minutes.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                    <h5>Minutes</h5>
                    <p class="text-muted">Meeting minutes and records</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.agendas.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-list-ul fa-3x text-info mb-3"></i>
                    <h5>Agendas</h5>
                    <p class="text-muted">Meeting agendas and topics</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.resolutions.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-gavel fa-3x text-danger mb-3"></i>
                    <h5>Resolutions</h5>
                    <p class="text-muted">Committee resolutions and decisions</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.public.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-globe fa-3x text-secondary mb-3"></i>
                    <h5>Public Documents</h5>
                    <p class="text-muted">Public access to committee documents</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="location.href='{{ route('committee.archive.index') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-archive fa-3x text-dark mb-3"></i>
                    <h5>Archive</h5>
                    <p class="text-muted">Historical committee records</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.module-card {
    cursor: pointer;
    transition: transform 0.2s;
}
.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection