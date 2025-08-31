@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Create New Market</h2>
                    <p class="text-muted">Add a new market facility</p>
                </div>
                <a href="{{ route('markets.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Markets
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('markets.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Market Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Market Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code') }}" required>
                                    <div class="form-text">Unique identifier for the market (e.g., MKT001)</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mb-3">Manager Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_name" class="form-label">Manager Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('manager_name') is-invalid @enderror"
                                           id="manager_name" name="manager_name" value="{{ old('manager_name') }}" required>
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_phone" class="form-label">Manager Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('manager_phone') is-invalid @enderror"
                                           id="manager_phone" name="manager_phone" value="{{ old('manager_phone') }}" required>
                                    @error('manager_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_email" class="form-label">Manager Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('manager_email') is-invalid @enderror"
                                           id="manager_email" name="manager_email" value="{{ old('manager_email') }}" required>
                                    @error('manager_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Market Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_area" class="form-label">Total Area (m²) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('total_area') is-invalid @enderror"
                                           id="total_area" name="total_area" value="{{ old('total_area') }}" required>
                                    @error('total_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_stalls" class="form-label">Total Number of Stalls <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_stalls') is-invalid @enderror"
                                           id="total_stalls" name="total_stalls" value="{{ old('total_stalls') }}" required min="1">
                                    @error('total_stalls')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Operating Schedule</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Operating Days <span class="text-danger">*</span></label>
                                    <div class="row">
                                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                        <div class="col-6 col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="operating_days[]" value="{{ $day }}"
                                                       id="day_{{ $day }}"
                                                       {{ in_array($day, old('operating_days', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $day }}">
                                                    {{ ucfirst($day) }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('operating_days')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Operating Hours <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="open_time" class="form-label">Open Time</label>
                                            <input type="time" class="form-control @error('operating_hours.open') is-invalid @enderror"
                                                   name="operating_hours[open]" value="{{ old('operating_hours.open', '06:00') }}">
                                        </div>
                                        <div class="col-6">
                                            <label for="close_time" class="form-label">Close Time</label>
                                            <input type="time" class="form-control @error('operating_hours.close') is-invalid @enderror"
                                                   name="operating_hours[close]" value="{{ old('operating_hours.close', '18:00') }}">
                                        </div>
                                    </div>
                                    @error('operating_hours')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('markets.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Market
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection