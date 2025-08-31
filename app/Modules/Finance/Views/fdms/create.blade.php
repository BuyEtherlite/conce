@extends('layouts.admin')

@section('title', 'Create FDMS Receipt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create FDMS Receipt</h3>
                    <div class="card-tools">
                        <a href="{{ route('finance.fdms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Receipts
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('finance.fdms.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number">Receipt Number</label>
                                    <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" 
                                           id="receipt_number" name="receipt_number" 
                                           value="{{ old('receipt_number') }}" 
                                           placeholder="Auto-generated if left blank">
                                    @error('receipt_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id">
                                        <option value="">Walk-in Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pos_terminal_id">POS Terminal <span class="text-danger">*</span></label>
                                    <select class="form-control @error('pos_terminal_id') is-invalid @enderror" 
                                            id="pos_terminal_id" name="pos_terminal_id" required>
                                        <option value="">Select Terminal</option>
                                        @foreach($posTerminals as $terminal)
                                            <option value="{{ $terminal->id }}" {{ old('pos_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                                {{ $terminal->terminal_name }} - {{ $terminal->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pos_terminal_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_date">Receipt Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" 
                                           id="receipt_date" name="receipt_date" 
                                           value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                                    @error('receipt_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" step="0.01" min="0"
                                           value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">Currency <span class="text-danger">*</span></label>
                                    <select class="form-control @error('currency') is-invalid @enderror" 
                                            id="currency" name="currency" required>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="ZWL" {{ old('currency') == 'ZWL' ? 'selected' : '' }}>ZWL</option>
                                        <option value="ZAR" {{ old('currency') == 'ZAR' ? 'selected' : '' }}>ZAR</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fiscal_day">Fiscal Day <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('fiscal_day') is-invalid @enderror" 
                                           id="fiscal_day" name="fiscal_day" 
                                           value="{{ old('fiscal_day', date('Ymd')) }}" required>
                                    @error('fiscal_day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fiscal_number">Fiscal Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('fiscal_number') is-invalid @enderror" 
                                           id="fiscal_number" name="fiscal_number" 
                                           value="{{ old('fiscal_number') }}" required>
                                    @error('fiscal_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                           id="description" name="description" 
                                           value="{{ old('description') }}" 
                                           placeholder="Receipt description">
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Receipt
                        </button>
                        <a href="{{ route('finance.fdms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
