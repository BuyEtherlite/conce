@extends('layouts.admin')

@section('page-title', 'Create Water Rate')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>💧 Water Management</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('water.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('water.connections.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-plug me-2"></i>Connections
                    </a>
                    <a href="{{ route('water.meters.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Meters & Readings
                    </a>
                    <a href="{{ route('water.billing.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Billing
                    </a>
                    <a href="{{ route('water.rates.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-dollar-sign me-2"></i>Water Rates
                    </a>
                    <a href="{{ route('water.quality.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-flask me-2"></i>Quality Testing
                    </a>
                    <a href="{{ route('water.infrastructure.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tools me-2"></i>Infrastructure
                    </a>
                    <a href="{{ route('water.reports.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>💰 Create Water Rate</h4>
                <a href="{{ route('water.rates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Rates
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Water Rate Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('water.rates.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rate_name" class="form-label">Rate Name *</label>
                                <input type="text" class="form-control" id="rate_name" name="rate_name" 
                                       value="{{ old('rate_name') }}" required 
                                       placeholder="e.g., Standard Residential Rate 2025">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="connection_type" class="form-label">Connection Type *</label>
                                <select class="form-select" id="connection_type" name="connection_type" required>
                                    <option value="">Select Connection Type</option>
                                    <option value="residential" {{ old('connection_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('connection_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('connection_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                    <option value="institutional" {{ old('connection_type') == 'institutional' ? 'selected' : '' }}>Institutional</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="base_charge" class="form-label">Base Charge ($) *</label>
                                <input type="number" class="form-control" id="base_charge" name="base_charge" 
                                       step="0.01" min="0" value="{{ old('base_charge', '15.00') }}" required>
                                <div class="form-text">Fixed monthly charge regardless of consumption</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="rate_per_unit" class="form-label">Rate per Unit ($/kL) *</label>
                                <input type="number" class="form-control" id="rate_per_unit" name="rate_per_unit" 
                                       step="0.01" min="0" value="{{ old('rate_per_unit', '2.50') }}" required>
                                <div class="form-text">Cost per kiloliter consumed</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="minimum_charge" class="form-label">Minimum Charge ($) *</label>
                                <input type="number" class="form-control" id="minimum_charge" name="minimum_charge" 
                                       step="0.01" min="0" value="{{ old('minimum_charge', '20.00') }}" required>
                                <div class="form-text">Minimum monthly bill amount</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="effective_date" class="form-label">Effective Date *</label>
                                <input type="date" class="form-control" id="effective_date" name="effective_date" 
                                       value="{{ old('effective_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Rate
                                    </label>
                                    <div class="form-text">Only active rates will be used for billing calculations</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Billing Calculation:</strong> 
                            Total Bill = Base Charge + (Consumption × Rate per Unit), but never less than Minimum Charge
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('page-title', 'Create Water Rate')

@section('content')
<div class="container-fluid">
    <!-- Water Management Side Navigation -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">💧 Water Management</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('water.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('water.connections.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-link me-2"></i>Water Connections
                    </a>
                    <a href="{{ route('water.meters.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Meter Management
                    </a>
                    <a href="{{ route('water.billing.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Billing
                    </a>
                    <a href="{{ route('water.quality.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-flask me-2"></i>Quality Control
                    </a>
                    <a href="{{ route('water.rates.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-dollar-sign me-2"></i>Water Rates
                    </a>
                    <a href="{{ route('water.infrastructure.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cogs me-2"></i>Infrastructure
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>💰 Create Water Rate</h4>
                <a href="{{ route('water.rates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Rates
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Water Rate</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('water.rates.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Rate Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rate_type" class="form-label">Rate Type *</label>
                                <select class="form-select" id="rate_type" name="rate_type" required>
                                    <option value="">Select Rate Type</option>
                                    <option value="standard" {{ old('rate_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="domestic" {{ old('rate_type') == 'domestic' ? 'selected' : '' }}>Domestic</option>
                                    <option value="commercial" {{ old('rate_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('rate_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rate_per_unit" class="form-label">Rate per m³ ($) *</label>
                                <input type="number" class="form-control" id="rate_per_unit" name="rate_per_unit" 
                                       step="0.01" min="0" value="{{ old('rate_per_unit', '2.50') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="minimum_charge" class="form-label">Minimum Charge ($)</label>
                                <input type="number" class="form-control" id="minimum_charge" name="minimum_charge" 
                                       step="0.01" min="0" value="{{ old('minimum_charge', '10.00') }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_charge" class="form-label">Service Charge ($)</label>
                                <input type="number" class="form-control" id="service_charge" name="service_charge" 
                                       step="0.01" min="0" value="{{ old('service_charge', '5.00') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="connection_fee" class="form-label">Connection Fee ($)</label>
                                <input type="number" class="form-control" id="connection_fee" name="connection_fee" 
                                       step="0.01" min="0" value="{{ old('connection_fee', '50.00') }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="effective_date" class="form-label">Effective Date *</label>
                                <input type="date" class="form-control" id="effective_date" name="effective_date" 
                                       value="{{ old('effective_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Rate
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Create Rate</button>
                            <a href="{{ route('water.rates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
