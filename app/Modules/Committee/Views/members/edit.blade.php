@extends('layouts.admin')

@section('page-title', 'Edit Committee Member')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>✏️ Edit Committee Member</h4>
        <a href="{{ route('committee.members.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Members
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('committee.members.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="committee_id" class="form-label">Committee</label>
                            <select class="form-select" id="committee_id" name="committee_id" required>
                                <option value="">Select Committee</option>
                                @foreach($committees as $committee)
                                    <option value="{{ $committee->id }}" {{ $member->committee_id == $committee->id ? 'selected' : '' }}>
                                        {{ $committee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="member_name" class="form-label">Member Name</label>
                            <input type="text" class="form-control" id="member_name" name="member_name" 
                                   value="{{ $member->member_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" 
                                   value="{{ $member->position }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                   value="{{ $member->appointment_date }}">
                        </div>

                        <div class="mb-3">
                            <label for="term_end_date" class="form-label">Term End Date</label>
                            <input type="date" class="form-control" id="term_end_date" name="term_end_date" 
                                   value="{{ $member->term_end_date }}">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="contact_information" class="form-label">Contact Information</label>
                    <textarea class="form-control" id="contact_information" name="contact_information" rows="3">{{ $member->contact_information }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
