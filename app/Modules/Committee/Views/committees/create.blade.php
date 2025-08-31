@extends('layouts.app')

@section('page-title', 'Create Committee')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📋 Create New Committee</h4>
        <a href="{{ route('committee.committees.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Committees
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.committees.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Committee Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="chairman_name" class="form-label">Chairman Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('chairman_name') is-invalid @enderror" 
                                       id="chairman_name" name="chairman_name" value="{{ old('chairman_name') }}" required>
                                @error('chairman_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="secretary_name" class="form-label">Secretary Name</label>
                                <input type="text" class="form-control @error('secretary_name') is-invalid @enderror" 
                                       id="secretary_name" name="secretary_name" value="{{ old('secretary_name') }}">
                                @error('secretary_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="established_date" class="form-label">Established Date</label>
                                <input type="date" class="form-control @error('established_date') is-invalid @enderror" 
                                       id="established_date" name="established_date" value="{{ old('established_date') }}">
                                @error('established_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meeting_schedule" class="form-label">Meeting Schedule</label>
                            <input type="text" class="form-control @error('meeting_schedule') is-invalid @enderror" 
                                   id="meeting_schedule" name="meeting_schedule" value="{{ old('meeting_schedule') }}" 
                                   placeholder="e.g., Every first Monday of the month">
                            @error('meeting_schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('committee.committees.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Create Committee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
