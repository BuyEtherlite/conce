@extends('layouts.admin')

@section('title', 'Add New Property')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add New Property</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('property.index') }}">Property Management</a></li>
                        <li class="breadcrumb-item active">Add Property</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('property.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Property Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="property_type" class="form-label">Property Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('property_type') is-invalid @enderror" 
                                        id="property_type" name="property_type" required>
                                    <option value="">Select Type</option>
                                    @foreach(\App\Models\Property\Property::getPropertyTypes() as $key => $value)
                                        <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="suburb" class="form-label">Suburb <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('suburb') is-invalid @enderror" 
                                       id="suburb" name="suburb" value="{{ old('suburb') }}" required>
                                @error('suburb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                                <select class="form-select @error('province') is-invalid @enderror" 
                                        id="province" name="province" required>
                                    <option value="">Select Province</option>
                                    <option value="Eastern Cape" {{ old('province') == 'Eastern Cape' ? 'selected' : '' }}>Eastern Cape</option>
                                    <option value="Free State" {{ old('province') == 'Free State' ? 'selected' : '' }}>Free State</option>
                                    <option value="Gauteng" {{ old('province') == 'Gauteng' ? 'selected' : '' }}>Gauteng</option>
                                    <option value="KwaZulu-Natal" {{ old('province') == 'KwaZulu-Natal' ? 'selected' : '' }}>KwaZulu-Natal</option>
                                    <option value="Limpopo" {{ old('province') == 'Limpopo' ? 'selected' : '' }}>Limpopo</option>
                                    <option value="Mpumalanga" {{ old('province') == 'Mpumalanga' ? 'selected' : '' }}>Mpumalanga</option>
                                    <option value="Northern Cape" {{ old('province') == 'Northern Cape' ? 'selected' : '' }}>Northern Cape</option>
                                    <option value="North West" {{ old('province') == 'North West' ? 'selected' : '' }}>North West</option>
                                    <option value="Western Cape" {{ old('province') == 'Western Cape' ? 'selected' : '' }}>Western Cape</option>
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ownership_type" class="form-label">Ownership Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('ownership_type') is-invalid @enderror" 
                                        id="ownership_type" name="ownership_type" required>
                                    <option value="">Select Ownership Type</option>
                                    @foreach(\App\Models\Property\Property::getOwnershipTypes() as $key => $value)
                                        <option value="{{ $key }}" {{ old('ownership_type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ownership_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="erf_number" class="form-label">ERF Number</label>
                                <input type="text" class="form-control @error('erf_number') is-invalid @enderror" 
                                       id="erf_number" name="erf_number" value="{{ old('erf_number') }}">
                                @error('erf_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="title_deed_number" class="form-label">Title Deed Number</label>
                                <input type="text" class="form-control @error('title_deed_number') is-invalid @enderror" 
                                       id="title_deed_number" name="title_deed_number" value="{{ old('title_deed_number') }}">
                                @error('title_deed_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="size_sqm" class="form-label">Size (Square Meters)</label>
                                <input type="number" step="0.01" class="form-control @error('size_sqm') is-invalid @enderror" 
                                       id="size_sqm" name="size_sqm" value="{{ old('size_sqm') }}">
                                @error('size_sqm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="size_hectares" class="form-label">Size (Hectares)</label>
                                <input type="number" step="0.0001" class="form-control @error('size_hectares') is-invalid @enderror" 
                                       id="size_hectares" name="size_hectares" value="{{ old('size_hectares') }}">
                                @error('size_hectares')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="market_value" class="form-label">Market Value</label>
                                <input type="number" step="0.01" class="form-control @error('market_value') is-invalid @enderror" 
                                       id="market_value" name="market_value" value="{{ old('market_value') }}">
                                @error('market_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="municipal_value" class="form-label">Municipal Value</label>
                                <input type="number" step="0.01" class="form-control @error('municipal_value') is-invalid @enderror" 
                                       id="municipal_value" name="municipal_value" value="{{ old('municipal_value') }}">
                                @error('municipal_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Owner Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="owner_type" class="form-label">Owner Type</label>
                                <select class="form-select @error('owner_type') is-invalid @enderror" 
                                        id="owner_type" name="owner_type">
                                    <option value="">Select Owner Type</option>
                                    <option value="individual" {{ old('owner_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="company" {{ old('owner_type') == 'company' ? 'selected' : '' }}>Company</option>
                                </select>
                                @error('owner_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="individual_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="company_fields" style="display: none;">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label">ID/Registration Number</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                       id="id_number" name="id_number" value="{{ old('id_number') }}">
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ownership_percentage" class="form-label">Ownership Percentage</label>
                                <input type="number" step="0.01" min="0" max="100" 
                                       class="form-control @error('ownership_percentage') is-invalid @enderror" 
                                       id="ownership_percentage" name="ownership_percentage" value="{{ old('ownership_percentage', 100) }}">
                                @error('ownership_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Classification</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Property Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="zone_id" class="form-label">Property Zone</label>
                            <select class="form-select @error('zone_id') is-invalid @enderror" 
                                    id="zone_id" name="zone_id">
                                <option value="">Select Zone</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('zone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="council_id" class="form-label">Council <span class="text-danger">*</span></label>
                            <select class="form-select @error('council_id') is-invalid @enderror" 
                                    id="council_id" name="council_id" required>
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

                        <div class="mb-3">
                            <label for="office_id" class="form-label">Office <span class="text-danger">*</span></label>
                            <select class="form-select @error('office_id') is-invalid @enderror" 
                                    id="office_id" name="office_id" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('office_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create Property
                            </button>
                            <a href="{{ route('property.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ownerTypeSelect = document.getElementById('owner_type');
    const individualFields = document.getElementById('individual_fields');
    const companyFields = document.getElementById('company_fields');

    function toggleOwnerFields() {
        const ownerType = ownerTypeSelect.value;
        
        if (ownerType === 'individual') {
            individualFields.style.display = 'block';
            companyFields.style.display = 'none';
        } else if (ownerType === 'company') {
            individualFields.style.display = 'none';
            companyFields.style.display = 'block';
        } else {
            individualFields.style.display = 'none';
            companyFields.style.display = 'none';
        }
    }

    ownerTypeSelect.addEventListener('change', toggleOwnerFields);
    toggleOwnerFields(); // Initialize on page load
});
</script>
@endsection
