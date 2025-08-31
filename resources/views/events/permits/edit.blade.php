@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit Event Permit</h1>
                <a href="{{ route('events.permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Details
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('events.permits.update', $permit) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Event Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_category_id">Event Category</label>
                                    <select name="event_category_id" id="event_category_id" class="form-control @error('event_category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('event_category_id', $permit->event_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} (Fee: ${{ number_format($category->base_fee, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="event_name">Event Name</label>
                                    <input type="text" name="event_name" id="event_name" 
                                           class="form-control @error('event_name') is-invalid @enderror" 
                                           value="{{ old('event_name', $permit->event_name) }}" required>
                                    @error('event_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="event_description">Event Description</label>
                                    <textarea name="event_description" id="event_description" 
                                              class="form-control @error('event_description') is-invalid @enderror" 
                                              rows="4" required>{{ old('event_description', $permit->event_description) }}</textarea>
                                    @error('event_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Event Date</label>
                                    <input type="date" name="event_date" id="event_date" 
                                           class="form-control @error('event_date') is-invalid @enderror" 
                                           value="{{ old('event_date', $permit->event_date->format('Y-m-d')) }}" required>
                                    @error('event_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Start Time</label>
                                            <input type="time" name="start_time" id="start_time" 
                                                   class="form-control @error('start_time') is-invalid @enderror" 
                                                   value="{{ old('start_time', \Carbon\Carbon::parse($permit->start_time)->format('H:i')) }}" required>
                                            @error('start_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_time">End Time</label>
                                            <input type="time" name="end_time" id="end_time" 
                                                   class="form-control @error('end_time') is-invalid @enderror" 
                                                   value="{{ old('end_time', \Carbon\Carbon::parse($permit->end_time)->format('H:i')) }}" required>
                                            @error('end_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="expected_attendance">Expected Attendance</label>
                                    <input type="number" name="expected_attendance" id="expected_attendance" 
                                           class="form-control @error('expected_attendance') is-invalid @enderror" 
                                           value="{{ old('expected_attendance', $permit->expected_attendance) }}" min="1" required>
                                    @error('expected_attendance')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Venue Information -->
                        <h5 class="mb-3">Venue Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="venue">Venue Name</label>
                                    <input type="text" name="venue" id="venue" 
                                           class="form-control @error('venue') is-invalid @enderror" 
                                           value="{{ old('venue', $permit->venue) }}" required>
                                    @error('venue')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="venue_address">Venue Address</label>
                                    <textarea name="venue_address" id="venue_address" 
                                              class="form-control @error('venue_address') is-invalid @enderror" 
                                              rows="3" required>{{ old('venue_address', $permit->venue_address) }}</textarea>
                                    @error('venue_address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Organizer Information -->
                        <h5 class="mb-3">Organizer Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="organizer_name">Organizer Name</label>
                                    <input type="text" name="organizer_name" id="organizer_name" 
                                           class="form-control @error('organizer_name') is-invalid @enderror" 
                                           value="{{ old('organizer_name', $permit->organizer_name) }}" required>
                                    @error('organizer_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="organizer_email">Organizer Email</label>
                                    <input type="email" name="organizer_email" id="organizer_email" 
                                           class="form-control @error('organizer_email') is-invalid @enderror" 
                                           value="{{ old('organizer_email', $permit->organizer_email) }}" required>
                                    @error('organizer_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="organizer_phone">Organizer Phone</label>
                                    <input type="text" name="organizer_phone" id="organizer_phone" 
                                           class="form-control @error('organizer_phone') is-invalid @enderror" 
                                           value="{{ old('organizer_phone', $permit->organizer_phone) }}" required>
                                    @error('organizer_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" name="contact_person" id="contact_person" 
                                           class="form-control @error('contact_person') is-invalid @enderror" 
                                           value="{{ old('contact_person', $permit->contact_person) }}" required>
                                    @error('contact_person')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" name="contact_email" id="contact_email" 
                                           class="form-control @error('contact_email') is-invalid @enderror" 
                                           value="{{ old('contact_email', $permit->contact_email) }}" required>
                                    @error('contact_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="text" name="contact_phone" id="contact_phone" 
                                           class="form-control @error('contact_phone') is-invalid @enderror" 
                                           value="{{ old('contact_phone', $permit->contact_phone) }}" required>
                                    @error('contact_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="organizer_address">Organizer Address</label>
                            <textarea name="organizer_address" id="organizer_address" 
                                      class="form-control @error('organizer_address') is-invalid @enderror" 
                                      rows="3" required>{{ old('organizer_address', $permit->organizer_address) }}</textarea>
                            @error('organizer_address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <h5 class="mb-3">Additional Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="special_requirements">Special Requirements</label>
                                    <textarea name="special_requirements" id="special_requirements" 
                                              class="form-control @error('special_requirements') is-invalid @enderror" 
                                              rows="3">{{ old('special_requirements', $permit->special_requirements) }}</textarea>
                                    @error('special_requirements')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="equipment_needed">Equipment Needed</label>
                                    <textarea name="equipment_needed" id="equipment_needed" 
                                              class="form-control @error('equipment_needed') is-invalid @enderror" 
                                              rows="3">{{ old('equipment_needed', $permit->equipment_needed) }}</textarea>
                                    @error('equipment_needed')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <h5 class="mb-3">Services</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="alcohol_service" id="alcohol_service" 
                                           class="form-check-input" value="1"
                                           {{ old('alcohol_service', $permit->alcohol_service) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="alcohol_service">
                                        Alcohol Service
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="food_service" id="food_service" 
                                           class="form-check-input" value="1"
                                           {{ old('food_service', $permit->food_service) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="food_service">
                                        Food Service
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="amplified_sound" id="amplified_sound" 
                                           class="form-check-input" value="1"
                                           {{ old('amplified_sound', $permit->amplified_sound) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amplified_sound">
                                        Amplified Sound
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Permit
                            </button>
                            <a href="{{ route('events.permits.show', $permit) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
