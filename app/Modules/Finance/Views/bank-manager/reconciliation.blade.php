@extends('layouts.admin')

@section('title', 'Bank Reconciliation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Bank Reconciliation - {{ $bankAccount->account_name }}</h3>
                    <a href="{{ route('finance.bank-manager.show', $bankAccount->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Account
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.bank-manager.store-reconciliation', $bankAccount->id) }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reconciliation_date">Reconciliation Date</label>
                                    <input type="date" class="form-control" id="reconciliation_date" 
                                           name="reconciliation_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_statement_balance">Bank Statement Balance</label>
                                    <input type="number" class="form-control" id="bank_statement_balance" 
                                           name="bank_statement_balance" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <h5>Unreconciled Transactions</h5>
                        <p class="text-muted">Select transactions that appear on your bank statement:</p>

                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Reference</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="reconciled_transactions[]" 
                                                       value="{{ $transaction->id }}" class="transaction-checkbox">
                                            </td>
                                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $transaction->transaction_type == 'deposit' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($transaction->transaction_type) }}
                                                </span>
                                            </td>
                                            <td class="text-{{ $transaction->transaction_type == 'deposit' ? 'success' : 'danger' }}">
                                                {{ $transaction->transaction_type == 'deposit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-balance-scale"></i> Complete Reconciliation
                                </button>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                All transactions for this account have been reconciled.
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const transactionCheckboxes = document.querySelectorAll('.transaction-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        transactionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endsection
