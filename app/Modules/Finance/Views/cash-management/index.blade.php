@extends('layouts.admin')

@section('title', 'Cash Management')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3"><i class="fas fa-wallet me-2"></i>Cash Management</h1>
        </div>
    </div>

    <!-- Cash Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Cash</div>
                            <div class="h5 mb-0">${{ number_format($totalCash, 2) }}</div>
                        </div>
                        <div class="fas fa-dollar-sign fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Bank Accounts</div>
                            <div class="h5 mb-0">{{ $bankAccounts->count() }}</div>
                        </div>
                        <div class="fas fa-university fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Vouchers</div>
                            <div class="h5 mb-0">{{ $pendingVouchers }}</div>
                        </div>
                        <div class="fas fa-file-invoice fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Recent Transactions</div>
                            <div class="h5 mb-0">{{ $recentTransactions->count() }}</div>
                        </div>
                        <div class="fas fa-exchange-alt fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Bank Accounts -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-university me-1"></i>
                    Bank Accounts
                    <div class="float-end">
                        <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>New Account
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Bank</th>
                                    <th>Type</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bankAccounts as $account)
                                <tr>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>{{ $account->account_type }}</td>
                                    <td>${{ number_format($account->current_balance, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $account->is_active ? 'success' : 'danger' }}">
                                            {{ $account->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-outline-primary">
                            View All Accounts
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.cash-management.create-cash-receipt') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Record Cash Receipt
                        </a>
                        <a href="{{ route('finance.cash-management.create-cash-payment') }}" class="btn btn-warning">
                            <i class="fas fa-minus me-2"></i>Record Cash Payment
                        </a>
                        <a href="{{ route('finance.cash-management.cash-position') }}" class="btn btn-info">
                            <i class="fas fa-chart-pie me-2"></i>Cash Position
                        </a>
                        <a href="{{ route('finance.bank-reconciliation.index') }}" class="btn btn-secondary">
                            <i class="fas fa-balance-scale me-2"></i>Bank Reconciliation
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Recent Transactions
                </div>
                <div class="card-body">
                    @forelse($recentTransactions as $transaction)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <small class="text-muted">{{ $transaction->transaction_date->format('M d, Y') }}</small>
                            <div class="fw-bold">{{ Str::limit($transaction->description, 30) }}</div>
                            <small class="text-muted">{{ $transaction->bankAccount->account_name }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $transaction->transaction_type == 'receipt' ? 'success' : 'danger' }}">
                                {{ $transaction->transaction_type == 'receipt' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No recent transactions</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection