@extends('layouts.admin')

@section('title', 'Bank Account Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Account Summary Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $bankAccount->account_name }}</h3>
                    <div>
                        <a href="{{ route('finance.bank-manager.reconciliation', $bankAccount->id) }}" class="btn btn-warning">
                            <i class="fas fa-balance-scale"></i> Reconcile
                        </a>
                        <a href="{{ route('finance.bank-manager.statements', $bankAccount->id) }}" class="btn btn-info">
                            <i class="fas fa-file-alt"></i> Statements
                        </a>
                        <a href="{{ route('finance.bank-manager.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bank Manager
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Bank Name:</strong></td>
                                    <td>{{ $bankAccount->bank_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number:</strong></td>
                                    <td>{{ $bankAccount->account_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Type:</strong></td>
                                    <td>{{ $bankAccount->account_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Branch:</strong></td>
                                    <td>{{ $bankAccount->branch_name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h2 class="text-{{ $balance >= 0 ? 'success' : 'danger' }}">
                                    ${{ number_format($balance, 2) }}
                                </h2>
                                <p class="text-muted">Current Balance</p>
                                <span class="badge badge-{{ $bankAccount->is_active ? 'success' : 'secondary' }} badge-lg">
                                    {{ $bankAccount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Reference</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
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
                                        <td>
                                            @if($transaction->reconciled_at)
                                                <span class="badge badge-success">Reconciled</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $transactions->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5>No Transactions Found</h5>
                            <p class="text-muted">No transactions have been recorded for this account yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
