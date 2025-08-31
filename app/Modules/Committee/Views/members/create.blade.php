@extends('layouts.admin')

@section('title', 'Add Committee Member')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>👥 Add Committee Member</h4>
        <a href="{{ route('committee.members.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Members
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.members.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="committee_id" class="form-label">Committee</label>
                                    <select class="form-select @error('committee_id') is-invalid @enderror" id="committee_id" name="committee_id" required>
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
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="chairperson" {{ old('role') == 'chairperson' ? 'selected' : '' }}>Chairperson</option>
                                        <option value="vice_chairperson" {{ old('role') == 'vice_chairperson' ? 'selected' : '' }}>Vice Chairperson</option>
                                        <option value="secretary" {{ old('role') == 'secretary' ? 'selected' : '' }}>Secretary</option>
                                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Appointment Date</label>
                                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Add Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
