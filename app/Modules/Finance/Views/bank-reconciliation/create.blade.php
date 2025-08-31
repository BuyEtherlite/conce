@extends('layouts.admin')

@section('title', 'New Bank Reconciliation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 New Bank Reconciliation</h3>
                    <a href="{{ route('finance.bank-reconciliation.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.bank-reconciliation.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_account_id">Bank Account <span class="text-danger">*</span></label>
                                    <select name="bank_account_id" id="bank_account_id" class="form-control @error('bank_account_id') is-invalid @enderror" required>
                                        <option value="">Select Bank Account</option>
                                        @foreach($bankAccounts as $account)
                                            <option value="{{ $account->id }}" {{ old('bank_account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_name }} - {{ $account->bank_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reconciliation_date">Reconciliation Date <span class="text-danger">*</span></label>
                                    <input type="date" name="reconciliation_date" id="reconciliation_date" 
                                           class="form-control @error('reconciliation_date') is-invalid @enderror" 
                                           value="{{ old('reconciliation_date', date('Y-m-d')) }}" required>
                                    @error('reconciliation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statement_balance">Bank Statement Balance <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="statement_balance" id="statement_balance" 
                                               class="form-control @error('statement_balance') is-invalid @enderror" 
                                               value="{{ old('statement_balance') }}" required>
                                        @error('statement_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="book_balance">Book Balance <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="book_balance" id="book_balance" 
                                               class="form-control @error('book_balance') is-invalid @enderror" 
                                               value="{{ old('book_balance') }}" required>
                                        @error('book_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" rows="4" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Add any reconciliation notes or comments...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Reconciliation
                            </button>
                            <a href="{{ route('finance.bank-reconciliation.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
