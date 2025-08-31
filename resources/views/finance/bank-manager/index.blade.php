@extends('layouts.admin')

@section('title', 'Bank Manager')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 Bank Manager</h3>
                    <div>
                        <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Bank Account
                        </a>
                        <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Finance
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($bankAccounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>Bank Name</th>
                                        <th>Account Number</th>
                                        <th>Account Type</th>
                                        <th>Current Balance</th>
                                        <th>Last Reconciliation</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bankAccounts as $account)
                                    <tr>
                                        <td>
                                            <strong>{{ $account->account_name }}</strong>
                                        </td>
                                        <td>{{ $account->bank_name }}</td>
                                        <td>{{ $account->account_number }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $account->account_type }}</span>
                                        </td>
                                        <td>
                                            <span class="text-{{ $account->current_balance >= 0 ? 'success' : 'danger' }}">
                                                ${{ number_format($account->current_balance, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($account->reconciliations->isNotEmpty())
                                                {{ $account->reconciliations->first()->reconciliation_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $account->is_active ? 'success' : 'secondary' }}">
                                                {{ $account->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('finance.bank-manager.show', $account->id) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('finance.bank-manager.reconciliation', $account->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Reconcile">
                                                    <i class="fas fa-balance-scale"></i>
                                                </a>
                                                <a href="{{ route('finance.bank-manager.statements', $account->id) }}" 
                                                   class="btn btn-sm btn-secondary" title="Statements">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-university fa-3x text-muted mb-3"></i>
                            <h5>No Bank Accounts Found</h5>
                            <p class="text-muted">Create your first bank account to get started with bank management.</p>
                            <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Bank Account
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('finance.bank-manager.transfer-funds') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-exchange-alt"></i><br>
                                Transfer Funds
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.cash-management.index') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-chart-line"></i><br>
                                Cash Position
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-file-alt"></i><br>
                                Bank Reports
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.cash-management.bank-accounts.index') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-cog"></i><br>
                                Manage Accounts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
