@extends('layouts.admin')

@section('page-title', 'New Water Connection')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 New Water Connection</h4>
        <a href="{{ route('water.connections.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Connections
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Connection Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('water.connections.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="connection_number" class="form-label">Connection Number</label>
                                <input type="text" class="form-control @error('connection_number') is-invalid @enderror" 
                                       id="connection_number" name="connection_number" 
                                       value="{{ old('connection_number', 'WC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" required>
                                @error('connection_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="connection_type" class="form-label">Connection Type</label>
                                <select class="form-select @error('connection_type') is-invalid @enderror" 
                                        id="connection_type" name="connection_type" required>
                                    <option value="">Select Type</option>
                                    <option value="residential" {{ old('connection_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('connection_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('connection_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                    <option value="institutional" {{ old('connection_type') == 'institutional' ? 'selected' : '' }}>Institutional</option>
                                </select>
                                @error('connection_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Customer Phone</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Customer Email</label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
                            @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="property_address" class="form-label">Property Address</label>
                            <textarea class="form-control @error('property_address') is-invalid @enderror" 
                                      id="property_address" name="property_address" rows="3" required>{{ old('property_address') }}</textarea>
                            @error('property_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meter_size" class="form-label">Meter Size</label>
                                <select class="form-select @error('meter_size') is-invalid @enderror" 
                                        id="meter_size" name="meter_size" required>
                                    <option value="">Select Size</option>
                                    <option value="15mm" {{ old('meter_size') == '15mm' ? 'selected' : '' }}>15mm</option>
                                    <option value="20mm" {{ old('meter_size') == '20mm' ? 'selected' : '' }}>20mm</option>
                                    <option value="25mm" {{ old('meter_size') == '25mm' ? 'selected' : '' }}>25mm</option>
                                    <option value="32mm" {{ old('meter_size') == '32mm' ? 'selected' : '' }}>32mm</option>
                                    <option value="40mm" {{ old('meter_size') == '40mm' ? 'selected' : '' }}>40mm</option>
                                    <option value="50mm" {{ old('meter_size') == '50mm' ? 'selected' : '' }}>50mm</option>
                                </select>
                                @error('meter_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="installation_date" class="form-label">Installation Date</label>
                                <input type="date" class="form-control @error('installation_date') is-invalid @enderror" 
                                       id="installation_date" name="installation_date" 
                                       value="{{ old('installation_date', date('Y-m-d')) }}" required>
                                @error('installation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deposit_amount" class="form-label">Deposit Amount</label>
                                <input type="number" step="0.01" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                       id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount', '0.00') }}">
                                @error('deposit_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="monthly_rate" class="form-label">Monthly Base Rate</label>
                                <input type="number" step="0.01" class="form-control @error('monthly_rate') is-invalid @enderror" 
                                       id="monthly_rate" name="monthly_rate" value="{{ old('monthly_rate', '50.00') }}" required>
                                @error('monthly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('water.connections.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
