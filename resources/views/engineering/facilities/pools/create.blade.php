@extends('layouts.app')

@section('page-title', 'Add New Pool')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Add New Swimming Pool</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.pools') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Pools
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pool Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('facilities.pools.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Pool Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pool_type" class="form-label">Pool Type</label>
                                <select class="form-select" id="pool_type" name="pool_type">
                                    <option value="olympic">Olympic Pool</option>
                                    <option value="children">Children's Pool</option>
                                    <option value="therapy">Therapy Pool</option>
                                    <option value="leisure">Leisure Pool</option>
                                    <option value="diving">Diving Pool</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacity (people) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate (R) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                       id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" step="0.01" min="0" required>
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="length" class="form-label">Length (m)</label>
                                <input type="number" class="form-control" id="length" name="length" 
                                       value="{{ old('length') }}" step="0.1" min="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="width" class="form-label">Width (m)</label>
                                <input type="number" class="form-control" id="width" name="width" 
                                       value="{{ old('width') }}" step="0.1" min="0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="depth" class="form-label">Depth (m)</label>
                                <input type="number" class="form-control" id="depth" name="depth" 
                                       value="{{ old('depth') }}" step="0.1" min="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="available">Available</option>
                                    <option value="maintenance">Under Maintenance</option>
                                    <option value="closed">Temporarily Closed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amenities" class="form-label">Amenities</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="changing_rooms" name="amenities[]" value="changing_rooms">
                                        <label class="form-check-label" for="changing_rooms">Changing Rooms</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showers" name="amenities[]" value="showers">
                                        <label class="form-check-label" for="showers">Showers</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lockers" name="amenities[]" value="lockers">
                                        <label class="form-check-label" for="lockers">Lockers</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lifeguard" name="amenities[]" value="lifeguard">
                                        <label class="form-check-label" for="lifeguard">Lifeguard on Duty</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="heating" name="amenities[]" value="heating">
                                        <label class="form-check-label" for="heating">Heated Pool</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="wheelchair_access" name="amenities[]" value="wheelchair_access">
                                        <label class="form-check-label" for="wheelchair_access">Wheelchair Access</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="operating_hours" class="form-label">Operating Hours</label>
                            <textarea class="form-control" id="operating_hours" name="operating_hours" rows="2" placeholder="e.g., Mon-Fri: 6:00 AM - 10:00 PM, Sat-Sun: 8:00 AM - 8:00 PM">{{ old('operating_hours') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('facilities.pools') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Pool</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Pool Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Ensure pool name is unique</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Set appropriate capacity based on pool size</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Consider safety requirements</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Include all available amenities</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Set competitive pricing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
