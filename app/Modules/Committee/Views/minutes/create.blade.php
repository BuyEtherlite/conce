@extends('layouts.app')

@section('page-title', 'Create Meeting Minutes')

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
                <h4>Create Meeting Minutes</h4>
                <a href="{{ route('committee.minutes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.minutes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_id" class="form-label">Meeting <span class="text-danger">*</span></label>
                                    <select class="form-select @error('meeting_id') is-invalid @enderror" id="meeting_id" name="meeting_id" required>
                                        <option value="">Select Meeting</option>
                                        @foreach($meetings as $meeting)
                                            <option value="{{ $meeting->id }}" {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                                {{ $meeting->title }} - {{ $meeting->meeting_date->format('M d, Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('meeting_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minute_type" class="form-label">Minute Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('minute_type') is-invalid @enderror" id="minute_type" name="minute_type" required>
                                        <option value="">Select Type</option>
                                        <option value="discussion" {{ old('minute_type') === 'discussion' ? 'selected' : '' }}>Discussion</option>
                                        <option value="resolution" {{ old('minute_type') === 'resolution' ? 'selected' : '' }}>Resolution</option>
                                        <option value="decision" {{ old('minute_type') === 'decision' ? 'selected' : '' }}>Decision</option>
                                        <option value="action_item" {{ old('minute_type') === 'action_item' ? 'selected' : '' }}>Action Item</option>
                                        <option value="general" {{ old('minute_type') === 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('minute_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="recorded_by" class="form-label">Recorded By <span class="text-danger">*</span></label>
                            <select class="form-select @error('recorded_by') is-invalid @enderror" id="recorded_by" name="recorded_by" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('recorded_by') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('recorded_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Minutes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
