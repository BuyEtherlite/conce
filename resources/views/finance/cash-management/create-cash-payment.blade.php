@extends('layouts.app')

@section('title', 'Record Cash Payment')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-minus me-2"></i>Record Cash Payment</h1>
        <a href="{{ route('finance.cash-management.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Cash Management
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cash Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.cash-management.store-cash-payment') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bank_account_id" class="form-label">Bank Account *</label>
                                <select class="form-select @error('bank_account_id') is-invalid @enderror" 
                                        id="bank_account_id" name="bank_account_id" required>
                                    <option value="">Select Bank Account</option>
                                    @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id }}" 
                                            data-balance="{{ $account->current_balance }}"
                                            {{ old('bank_account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->account_name }} - {{ $account->bank_name }} 
                                        (Balance: ${{ number_format($account->current_balance, 2) }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_date" class="form-label">Transaction Date *</label>
                                <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                       id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small id="balance-info" class="text-muted"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="payee" class="form-label">Payee *</label>
                                <input type="text" class="form-control @error('payee') is-invalid @enderror" 
                                       id="payee" name="payee" value="{{ old('payee') }}" required>
                                @error('payee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="reference" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                                       id="reference" name="reference" value="{{ old('reference') }}">
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Record Payment
                            </button>
                            <a href="{{ route('finance.cash-management.index') }}" class="btn btn-secondary">
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
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Cash Payment</h6>
                        <p class="mb-0 small">
                            Record money paid from your bank account. This will decrease 
                            the selected account balance. Ensure sufficient funds are available.
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-1"></i>Examples</h6>
                        <ul class="mb-0 small">
                            <li>Supplier payments</li>
                            <li>Utility bills</li>
                            <li>Salaries and wages</li>
                            <li>Equipment purchases</li>
                            <li>Service fees</li>
                            <li>Loan repayments</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountSelect = document.getElementById('bank_account_id');
    const amountInput = document.getElementById('amount');
    const balanceInfo = document.getElementById('balance-info');

    function updateBalanceInfo() {
        const selectedOption = accountSelect.options[accountSelect.selectedIndex];
        if (selectedOption.dataset.balance) {
            const balance = parseFloat(selectedOption.dataset.balance);
            const amount = parseFloat(amountInput.value) || 0;
            
            balanceInfo.textContent = `Available balance: $${balance.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            
            if (amount > balance) {
                balanceInfo.className = 'text-danger';
                balanceInfo.textContent += ' - Insufficient funds!';
            } else {
                balanceInfo.className = 'text-muted';
            }
        }
    }

    accountSelect.addEventListener('change', updateBalanceInfo);
    amountInput.addEventListener('input', updateBalanceInfo);
    
    updateBalanceInfo();
});
</script>
@endsection
