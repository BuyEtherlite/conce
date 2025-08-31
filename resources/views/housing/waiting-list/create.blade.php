@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add to Waiting List</h4>
                    <a href="{{ route('housing.waiting-list.index') }}" class="btn btn-secondary float-right">Back to List</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('housing.waiting-list.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} - {{ $customer->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="housing_application_id">Housing Application</label>
                                    <select class="form-control @error('housing_application_id') is-invalid @enderror" 
                                            id="housing_application_id" name="housing_application_id" required>
                                        <option value="">Select Application</option>
                                        @foreach($applications as $application)
                                            <option value="{{ $application->id }}" {{ old('housing_application_id') == $application->id ? 'selected' : '' }}>
                                                APP-{{ $application->id }} - {{ $application->property_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('housing_application_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="priority">Priority (1-10)</label>
                                    <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                           id="priority" name="priority" value="{{ old('priority', 5) }}" 
                                           min="1" max="10" required>
                                    @error('priority')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="application_date">Application Date</label>
                                    <input type="date" class="form-control @error('application_date') is-invalid @enderror" 
                                           id="application_date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required>
                                    @error('application_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="family_size">Family Size</label>
                                    <input type="number" class="form-control @error('family_size') is-invalid @enderror" 
                                           id="family_size" name="family_size" value="{{ old('family_size') }}" 
                                           min="1" required>
                                    @error('family_size')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_location">Preferred Location</label>
                                    <input type="text" class="form-control @error('preferred_location') is-invalid @enderror" 
                                           id="preferred_location" name="preferred_location" value="{{ old('preferred_location') }}">
                                    @error('preferred_location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="allocated" {{ old('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="special_needs">Special Needs</label>
                            <textarea class="form-control @error('special_needs') is-invalid @enderror" 
                                      id="special_needs" name="special_needs" rows="3">{{ old('special_needs') }}</textarea>
                            @error('special_needs')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="income_verification">Income Verification</label>
                            <textarea class="form-control @error('income_verification') is-invalid @enderror" 
                                      id="income_verification" name="income_verification" rows="3">{{ old('income_verification') }}</textarea>
                            @error('income_verification')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add to Waiting List</button>
                            <a href="{{ route('housing.waiting-list.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
