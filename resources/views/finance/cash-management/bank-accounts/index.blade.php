@extends('layouts.app')

@section('title', 'Bank Accounts')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-university me-2"></i>Bank Accounts</h1>
        <div>
            <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Bank Account
            </a>
            <a href="{{ route('finance.cash-management.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Cash Management
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Bank</th>
                            <th>Account Number</th>
                            <th>Type</th>
                            <th>Current Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankAccounts as $account)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $account->account_name }}</div>
                                @if($account->description)
                                <small class="text-muted">{{ $account->description }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $account->bank_name }}</div>
                                @if($account->branch_code)
                                <small class="text-muted">Branch: {{ $account->branch_code }}</small>
                                @endif
                            </td>
                            <td>{{ $account->account_number }}</td>
                            <td>{{ $account->account_type }}</td>
                            <td>
                                <span class="fw-bold text-{{ $account->current_balance >= 0 ? 'success' : 'danger' }}">
                                    ${{ number_format($account->current_balance, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $account->is_active ? 'success' : 'danger' }}">
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-1"></i>View Details</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-1"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-history me-1"></i>Transaction History</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-ban me-1"></i>Deactivate</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-university fa-3x mb-3"></i>
                                    <p>No bank accounts found.</p>
                                    <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-primary">
                                        Create First Bank Account
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $bankAccounts->links() }}
        </div>
    </div>
</div>
@endsection
