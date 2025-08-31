@extends('layouts.admin')

@section('title', 'Cashbook Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">💰 Cashbook Management</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.cashbook.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Transaction
                        </a>
                        <a href="{{ route('finance.cashbook.reports') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Cash Balance Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Current Cash Balance</h5>
                                    <h3>${{ number_format($cashBalance, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Account</th>
                                    <th>Reference</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->transaction_type === 'receipt' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->account->account_name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->reference_number ?? '-' }}</td>
                                    <td>${{ number_format($transaction->amount, 2) }}</td>
                                    <td>
                                        @if($transaction->reconciled_at)
                                            <span class="badge bg-success">Reconciled</span>
                                        @else
                                            <span class="badge bg-warning">Unreconciled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No transactions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
