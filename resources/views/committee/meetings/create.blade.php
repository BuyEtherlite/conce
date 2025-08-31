@extends('layouts.app')

@section('page-title', 'Schedule Meeting')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📅 Schedule Committee Meeting</h4>
        <a href="{{ route('committee.meetings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Meetings
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.meetings.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="committee_id" class="form-label">Committee <span class="text-danger">*</span></label>
                                <select class="form-control @error('committee_id') is-invalid @enderror" id="committee_id" name="committee_id" required>
                                    <option value="">Select Committee</option>
                                    @foreach($committees as $committee)
                                        <option value="{{ $committee->id }}" {{ old('committee_id') == $committee->id ? 'selected' : '' }}>
                                            {{ $committee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('committee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meeting_type" class="form-label">Meeting Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('meeting_type') is-invalid @enderror" id="meeting_type" name="meeting_type" required>
                                    <option value="">Select Type</option>
                                    <option value="regular" {{ old('meeting_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="special" {{ old('meeting_type') == 'special' ? 'selected' : '' }}>Special</option>
                                    <option value="emergency" {{ old('meeting_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('meeting_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meeting_date" class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('meeting_date') is-invalid @enderror" 
                                       id="meeting_date" name="meeting_date" value="{{ old('meeting_date') }}" required>
                                @error('meeting_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meeting_time" class="form-label">Meeting Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('meeting_time') is-invalid @enderror" 
                                       id="meeting_time" name="meeting_time" value="{{ old('meeting_time') }}" required>
                                @error('meeting_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Council Chambers" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="agenda" class="form-label">Agenda</label>
                            <textarea class="form-control @error('agenda') is-invalid @enderror" 
                                      id="agenda" name="agenda" rows="5" 
                                      placeholder="Enter meeting agenda items...">{{ old('agenda') }}</textarea>
                            @error('agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('committee.meetings.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-check"></i> Schedule Meeting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
