@extends('layouts.admin')

@section('title', 'Create Chart of Account')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📋 Create Chart of Account</h1>
                <div>
                    <a href="{{ route('finance.chart-of-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Accounts
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('finance.chart-of-accounts.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_code" class="form-label">Account Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('account_code') is-invalid @enderror"
                                           id="account_code"
                                           name="account_code"
                                           value="{{ old('account_code') }}"
                                           required>
                                    @error('account_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_name" class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('account_name') is-invalid @enderror"
                                           id="account_name"
                                           name="account_name"
                                           value="{{ old('account_name') }}"
                                           required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('account_type') is-invalid @enderror"
                                            id="account_type"
                                            name="account_type"
                                            required>
                                        <option value="">Select Account Type</option>
                                        <option value="asset" {{ old('account_type') === 'asset' ? 'selected' : '' }}>Asset</option>
                                        <option value="liability" {{ old('account_type') === 'liability' ? 'selected' : '' }}>Liability</option>
                                        <option value="equity" {{ old('account_type') === 'equity' ? 'selected' : '' }}>Equity</option>
                                        <option value="revenue" {{ old('account_type') === 'revenue' ? 'selected' : '' }}>Revenue</option>
                                        <option value="expense" {{ old('account_type') === 'expense' ? 'selected' : '' }}>Expense</option>
                                    </select>
                                    @error('account_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_account_id" class="form-label">Parent Account</label>
                                    <select class="form-select @error('parent_account_id') is-invalid @enderror"
                                            id="parent_account_id"
                                            name="parent_account_id">
                                        <option value="">Select Parent Account (Optional)</option>
                                        @foreach($parentAccounts as $parentAccount)
                                            <option value="{{ $parentAccount->id }}" {{ old('parent_account_id') == $parentAccount->id ? 'selected' : '' }}>
                                                {{ $parentAccount->account_code }} - {{ $parentAccount->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_balance" class="form-label">Opening Balance</label>
                                    <input type="number"
                                           class="form-control @error('opening_balance') is-invalid @enderror"
                                           id="opening_balance"
                                           name="opening_balance"
                                           value="{{ old('opening_balance', '0.00') }}"
                                           step="0.01">
                                    @error('opening_balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Account
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('finance.chart-of-accounts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection