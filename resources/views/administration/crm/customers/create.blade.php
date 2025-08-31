@extends('layouts.app')

@section('page-title', 'Add New Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>👥 Add New Customer</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('administration.index') }}">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.crm.index') }}">CRM</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.customers') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('administration.customers') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Customers
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('administration.customers.store') }}" method="POST">
                        @csrf

                        <!-- Personal Information -->
                        <h6 class="text-muted mb-3">Personal Information</h6>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="title" class="form-label">Title</label>
                                <select class="form-select @error('title') is-invalid @enderror" id="title" name="title">
                                    <option value="">Select...</option>
                                    <option value="Mr" {{ old('title') === 'Mr' ? 'selected' : '' }}>Mr</option>
                                    <option value="Mrs" {{ old('title') === 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                    <option value="Ms" {{ old('title') === 'Ms' ? 'selected' : '' }}>Ms</option>
                                    <option value="Dr" {{ old('title') === 'Dr' ? 'selected' : '' }}>Dr</option>
                                    <option value="Prof" {{ old('title') === 'Prof' ? 'selected' : '' }}>Prof</option>
                                </select>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="id_number" class="form-label">ID Number *</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                       id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h6 class="text-muted mb-3 mt-4">Contact Information</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="alternative_phone" class="form-label">Alternative Phone</label>
                                <input type="text" class="form-control @error('alternative_phone') is-invalid @enderror" 
                                       id="alternative_phone" name="alternative_phone" value="{{ old('alternative_phone') }}">
                                @error('alternative_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="physical_address" class="form-label">Physical Address *</label>
                                <textarea class="form-control @error('physical_address') is-invalid @enderror" 
                                          id="physical_address" name="physical_address" rows="3" required>{{ old('physical_address') }}</textarea>
                                @error('physical_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="postal_address" class="form-label">Postal Address</label>
                                <textarea class="form-control @error('postal_address') is-invalid @enderror" 
                                          id="postal_address" name="postal_address" rows="3">{{ old('postal_address') }}</textarea>
                                @error('postal_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <h6 class="text-muted mb-3 mt-4">Additional Information</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" name="nationality" value="{{ old('nationality', 'South African') }}">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                       id="occupation" name="occupation" value="{{ old('occupation') }}">
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="employer" class="form-label">Employer</label>
                                <input type="text" class="form-control @error('employer') is-invalid @enderror" 
                                       id="employer" name="employer" value="{{ old('employer') }}">
                                @error('employer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="monthly_income" class="form-label">Monthly Income</label>
                                <input type="number" class="form-control @error('monthly_income') is-invalid @enderror" 
                                       id="monthly_income" name="monthly_income" value="{{ old('monthly_income') }}" step="0.01" min="0">
                                @error('monthly_income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="council_id" class="form-label">Council</label>
                                <select class="form-select @error('council_id') is-invalid @enderror" id="council_id" name="council_id">
                                    <option value="">Select Council</option>
                                    @foreach($councils as $council)
                                        <option value="{{ $council->id }}" {{ old('council_id') == $council->id ? 'selected' : '' }}>
                                            {{ $council->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('council_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <a href="{{ route('administration.customers') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        A unique customer number will be automatically generated when you create the customer.
                    </p>
                    <p class="text-muted small">
                        Fields marked with * are required.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection