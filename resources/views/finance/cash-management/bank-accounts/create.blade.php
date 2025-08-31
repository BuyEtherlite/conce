@extends('layouts.app')

@section('title', 'Create Bank Account')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-plus me-2"></i>Create Bank Account</h1>
        <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Bank Accounts
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bank Account Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.cash-management.bank-accounts.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">Account Name *</label>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                       id="account_name" name="account_name" value="{{ old('account_name') }}" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bank_name" class="form-label">Bank Name *</label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                       id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_number" class="form-label">Account Number *</label>
                                <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                       id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="account_type" class="form-label">Account Type *</label>
                                <select class="form-select @error('account_type') is-invalid @enderror" 
                                        id="account_type" name="account_type" required>
                                    <option value="">Select Account Type</option>
                                    <option value="Current" {{ old('account_type') == 'Current' ? 'selected' : '' }}>Current</option>
                                    <option value="Savings" {{ old('account_type') == 'Savings' ? 'selected' : '' }}>Savings</option>
                                    <option value="Fixed Deposit" {{ old('account_type') == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                    <option value="Call Account" {{ old('account_type') == 'Call Account' ? 'selected' : '' }}>Call Account</option>
                                </select>
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="branch_code" class="form-label">Branch Code</label>
                                <input type="text" class="form-control @error('branch_code') is-invalid @enderror" 
                                       id="branch_code" name="branch_code" value="{{ old('branch_code') }}">
                                @error('branch_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="swift_code" class="form-label">SWIFT Code</label>
                                <input type="text" class="form-control @error('swift_code') is-invalid @enderror" 
                                       id="swift_code" name="swift_code" value="{{ old('swift_code') }}">
                                @error('swift_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="opening_balance" class="form-label">Opening Balance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('opening_balance') is-invalid @enderror" 
                                           id="opening_balance" name="opening_balance" value="{{ old('opening_balance', '0.00') }}" required>
                                    @error('opening_balance')
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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Bank Account
                            </button>
                            <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Help & Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Required Fields</h6>
                        <ul class="mb-0 small">
                            <li>Account Name: A descriptive name for the account</li>
                            <li>Bank Name: The name of the banking institution</li>
                            <li>Account Number: Unique account identifier</li>
                            <li>Account Type: The type of bank account</li>
                            <li>Opening Balance: Initial balance when creating the account</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Note</h6>
                        <p class="mb-0 small">
                            The opening balance will be set as the current balance. 
                            This account will be available for cash management operations once created.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Create Bank Account')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 Create New Bank Account</h3>
                    <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Bank Accounts
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('finance.cash-management.bank-accounts.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="account_name" class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="account_name" name="account_name" 
                                           value="{{ old('account_name') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           value="{{ old('bank_name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" 
                                           value="{{ old('account_number') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="account_type" name="account_type" required>
                                        <option value="">Select Account Type</option>
                                        <option value="Current" {{ old('account_type') == 'Current' ? 'selected' : '' }}>Current Account</option>
                                        <option value="Savings" {{ old('account_type') == 'Savings' ? 'selected' : '' }}>Savings Account</option>
                                        <option value="Fixed Deposit" {{ old('account_type') == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                        <option value="Investment" {{ old('account_type') == 'Investment' ? 'selected' : '' }}>Investment Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="branch_code" class="form-label">Branch Code</label>
                                    <input type="text" class="form-control" id="branch_code" name="branch_code" 
                                           value="{{ old('branch_code') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="swift_code" class="form-label">SWIFT Code</label>
                                    <input type="text" class="form-control" id="swift_code" name="swift_code" 
                                           value="{{ old('swift_code') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="opening_balance" class="form-label">Opening Balance <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="opening_balance" name="opening_balance" 
                                               step="0.01" min="0" value="{{ old('opening_balance', '0.00') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Bank Account
                                </button>
                                <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
