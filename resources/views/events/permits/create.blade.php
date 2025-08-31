@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar-plus"></i> New Event Permit Application</h2>
                <a href="{{ route('events.permits.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Permits
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Permit Application Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.permits.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Event Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Event Information</h6>
                                
                                <div class="mb-3">
                                    <label for="event_name" class="form-label">Event Name *</label>
                                    <input type="text" class="form-control @error('event_name') is-invalid @enderror" 
                                           id="event_name" name="event_name" value="{{ old('event_name') }}" required>
                                    @error('event_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="event_description" class="form-label">Event Description *</label>
                                    <textarea class="form-control @error('event_description') is-invalid @enderror" 
                                              id="event_description" name="event_description" rows="4" required>{{ old('event_description') }}</textarea>
                                    @error('event_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="event_category_id" class="form-label">Event Category *</label>
                                    <select class="form-select @error('event_category_id') is-invalid @enderror" 
                                            id="event_category_id" name="event_category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('event_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} - R{{ number_format($category->fee_amount, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="event_date" class="form-label">Event Date *</label>
                                            <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                                   id="event_date" name="event_date" value="{{ old('event_date') }}" 
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                            @error('event_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time *</label>
                                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                                   id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">End Time *</label>
                                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                                   id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="venue" class="form-label">Venue *</label>
                                    <input type="text" class="form-control @error('venue') is-invalid @enderror" 
                                           id="venue" name="venue" value="{{ old('venue') }}" required>
                                    @error('venue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="expected_attendance" class="form-label">Expected Attendance *</label>
                                    <input type="number" class="form-control @error('expected_attendance') is-invalid @enderror" 
                                           id="expected_attendance" name="expected_attendance" value="{{ old('expected_attendance') }}" 
                                           min="1" required>
                                    @error('expected_attendance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Organizer Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Organizer Information</h6>
                                
                                <div class="mb-3">
                                    <label for="organizer_name" class="form-label">Organizer Name *</label>
                                    <input type="text" class="form-control @error('organizer_name') is-invalid @enderror" 
                                           id="organizer_name" name="organizer_name" value="{{ old('organizer_name') }}" required>
                                    @error('organizer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="organizer_contact" class="form-label">Contact Number *</label>
                                    <input type="text" class="form-control @error('organizer_contact') is-invalid @enderror" 
                                           id="organizer_contact" name="organizer_contact" value="{{ old('organizer_contact') }}" required>
                                    @error('organizer_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="organizer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('organizer_email') is-invalid @enderror" 
                                           id="organizer_email" name="organizer_email" value="{{ old('organizer_email') }}" required>
                                    @error('organizer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="organizer_address" class="form-label">Address</label>
                                    <textarea class="form-control @error('organizer_address') is-invalid @enderror" 
                                              id="organizer_address" name="organizer_address" rows="3">{{ old('organizer_address') }}</textarea>
                                    @error('organizer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Additional Requirements -->
                                <h6 class="text-primary mb-3 mt-4">Additional Information</h6>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_alcohol_license" 
                                               name="requires_alcohol_license" value="1" {{ old('requires_alcohol_license') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_alcohol_license">
                                            Requires Alcohol License
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_amplified_sound" 
                                               name="requires_amplified_sound" value="1" {{ old('requires_amplified_sound') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_amplified_sound">
                                            Requires Amplified Sound
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_road_closure" 
                                               name="requires_road_closure" value="1" {{ old('requires_road_closure') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_road_closure">
                                            Requires Road Closure
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="special_requirements" class="form-label">Special Requirements</label>
                                    <textarea class="form-control @error('special_requirements') is-invalid @enderror" 
                                              id="special_requirements" name="special_requirements" rows="3">{{ old('special_requirements') }}</textarea>
                                    @error('special_requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Important Information</h6>
                                    <ul class="mb-0">
                                        <li>Event permit applications must be submitted at least 14 days before the event date</li>
                                        <li>All required documents must be uploaded before the permit can be approved</li>
                                        <li>Permit fees are determined by the event category and expected attendance</li>
                                        <li>Events requiring road closures need additional approvals from Traffic Department</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('events.permits.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Submit Application
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
