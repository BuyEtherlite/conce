@extends('layouts.admin')

@section('title', 'Edit Bank Reconciliation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 Edit Bank Reconciliation</h3>
                    <a href="{{ route('finance.bank-reconciliation.show', $reconciliation->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Details
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.bank-reconciliation.update', $reconciliation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_account_id">Bank Account</label>
                                    <select name="bank_account_id" id="bank_account_id" class="form-control" disabled>
                                        @foreach($bankAccounts as $account)
                                            <option value="{{ $account->id }}" {{ $reconciliation->bank_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_name }} - {{ $account->bank_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Bank account cannot be changed after creation.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reconciliation_date">Reconciliation Date</label>
                                    <input type="date" name="reconciliation_date" id="reconciliation_date" 
                                           class="form-control" 
                                           value="{{ $reconciliation->reconciliation_date->format('Y-m-d') }}" disabled>
                                    <small class="text-muted">Reconciliation date cannot be changed after creation.</small>
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
                                               value="{{ old('statement_balance', $reconciliation->statement_balance) }}" required>
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
                                               value="{{ old('book_balance', $reconciliation->book_balance) }}" required>
                                        @error('book_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $reconciliation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ old('status', $reconciliation->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="reconciled" {{ old('status', $reconciliation->status) == 'reconciled' ? 'selected' : '' }}>Reconciled</option>
                                        <option value="discrepancy" {{ old('status', $reconciliation->status) == 'discrepancy' ? 'selected' : '' }}>Discrepancy</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" rows="4" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Add any reconciliation notes or comments...">{{ old('notes', $reconciliation->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Reconciliation
                            </button>
                            <a href="{{ route('finance.bank-reconciliation.show', $reconciliation->id) }}" class="btn btn-secondary ml-2">
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
