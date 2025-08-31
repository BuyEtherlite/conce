@extends('layouts.admin')

@section('title', 'Edit Receipt')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>💰 Edit Receipt #{{ $receipt->receipt_number }}</h1>
        <div>
            <a href="{{ route('finance.accounts-receivable.receipts.show', $receipt) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('finance.accounts-receivable.receipts.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Receipts
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Receipt Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.accounts-receivable.receipts.update', $receipt) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receipt_number" class="form-label">Receipt Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" 
                                           id="receipt_number" name="receipt_number" 
                                           value="{{ old('receipt_number', $receipt->receipt_number) }}" required>
                                    @error('receipt_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" 
                                                {{ (old('customer_id', $receipt->customer_id) == $customer->id) ? 'selected' : '' }}>
                                                {{ $customer->customer_name }}
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
                                <div class="mb-3">
                                    <label for="receipt_date" class="form-label">Receipt Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" 
                                           id="receipt_date" name="receipt_date" 
                                           value="{{ old('receipt_date', $receipt->receipt_date ? $receipt->receipt_date->format('Y-m-d') : '') }}" required>
                                    @error('receipt_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount_received" class="form-label">Amount Received <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R</span>
                                        <input type="number" class="form-control @error('amount_received') is-invalid @enderror" 
                                               id="amount_received" name="amount_received" step="0.01" min="0"
                                               value="{{ old('amount_received', $receipt->amount_received) }}" required>
                                        @error('amount_received')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method', $receipt->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="cheque" {{ old('payment_method', $receipt->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="bank_transfer" {{ old('payment_method', $receipt->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="card" {{ old('payment_method', $receipt->payment_method) == 'card' ? 'selected' : '' }}>Card Payment</option>
                                        <option value="eft" {{ old('payment_method', $receipt->payment_method) == 'eft' ? 'selected' : '' }}>EFT</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_reference" class="form-label">Payment Reference</label>
                                    <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" 
                                           id="payment_reference" name="payment_reference" 
                                           value="{{ old('payment_reference', $receipt->payment_reference) }}" 
                                           placeholder="Cheque number, reference number, etc.">
                                    @error('payment_reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Bank Account</label>
                            <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                   id="bank_account" name="bank_account" 
                                   value="{{ old('bank_account', $receipt->bank_account) }}" 
                                   placeholder="Bank account where payment was received">
                            @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3"
                                      placeholder="Additional notes about this receipt">{{ old('notes', $receipt->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('finance.accounts-receivable.receipts.show', $receipt) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Receipt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
