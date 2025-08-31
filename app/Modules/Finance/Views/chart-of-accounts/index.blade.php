@extends('layouts.admin')

@section('title', 'Chart of Accounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📋 Chart of Accounts</h1>
                <div>
                    <a href="{{ route('finance.chart-of-accounts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Account
                    </a>
                    <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Finance
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Listing</h5>
                </div>
                <div class="card-body">
                    @if(count($accounts) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Type</th>
                                        <th>Subtype</th>
                                        <th>Parent Account</th>
                                        <th class="text-end">Opening Balance</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                        <tr>
                                            <td><strong>{{ $account->account_code }}</strong></td>
                                            <td>{{ $account->account_name }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($account->account_type) }}</span>
                                            </td>
                                            <td>{{ str_replace('_', ' ', ucfirst($account->account_subtype)) }}</td>
                                            <td>
                                                @if($account->parent)
                                                    {{ $account->parent->account_code }} - {{ $account->parent->account_name }}
                                                @else
                                                    <span class="text-muted">Root Account</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                ${{ number_format($account->opening_balance, 2) }}
                                            </td>
                                            <td class="text-center">
                                                @if($account->active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('finance.chart-of-accounts.show', $account->id) }}" 
                                                       class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('finance.chart-of-accounts.edit', $account->id) }}" 
                                                       class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('finance.chart-of-accounts.destroy', $account->id) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this account?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-2">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6>Total Accounts</h6>
                                        <h4>{{ count($accounts) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6>Assets</h6>
                                        <h4>{{ $accounts->where('account_type', 'asset')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h6>Liabilities</h6>
                                        <h4>{{ $accounts->where('account_type', 'liability')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h6>Equity</h6>
                                        <h4>{{ $accounts->where('account_type', 'equity')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h6>Revenue</h6>
                                        <h4>{{ $accounts->where('account_type', 'revenue')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <h6>Expenses</h6>
                                        <h4>{{ $accounts->where('account_type', 'expense')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Accounts Found</h5>
                            <p class="text-muted">Start by creating your first chart of account.</p>
                            <a href="{{ route('finance.chart-of-accounts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Account
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection