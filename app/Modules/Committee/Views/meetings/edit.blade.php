@extends('layouts.app')

@section('page-title', 'Edit Meeting')

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
                <a href="{{ route('committee.meetings.index') }}" class="list-group-item list-group-item-action active">
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
                <h4>Edit Meeting</h4>
                <a href="{{ route('committee.meetings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.meetings.update', $meeting->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="committee_id" class="form-label">Committee <span class="text-danger">*</span></label>
                                    <select class="form-select @error('committee_id') is-invalid @enderror" id="committee_id" name="committee_id" required>
                                        <option value="">Select Committee</option>
                                        @foreach($committees as $committee)
                                            <option value="{{ $committee->id }}" {{ old('committee_id', $meeting->committee_id) == $committee->id ? 'selected' : '' }}>
                                                {{ $committee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('committee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="scheduled" {{ old('status', $meeting->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="completed" {{ old('status', $meeting->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $meeting->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $meeting->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_date" class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('meeting_date') is-invalid @enderror" 
                                           id="meeting_date" name="meeting_date" value="{{ old('meeting_date', $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : '') }}" required>
                                    @error('meeting_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time', $meeting->start_time) }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $meeting->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Meeting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
