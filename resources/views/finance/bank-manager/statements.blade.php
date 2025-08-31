@extends('layouts.admin')

@section('title', 'Bank Statements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Bank Statement - {{ $bankAccount->account_name }}</h3>
                    <a href="{{ route('finance.bank-manager.show', $bankAccount->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Account
                    </a>
                </div>

                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" 
                                           name="start_date" value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" 
                                           name="end_date" value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Balance Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Opening Balance</h5>
                                    <h3>${{ number_format($openingBalance, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Closing Balance</h5>
                                    <h3>${{ number_format($closingBalance, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Net Movement</h5>
                                    <h3>${{ number_format($closingBalance - $openingBalance, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Reference</th>
                                        <th>Deposits</th>
                                        <th>Withdrawals</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $runningBalance = $openingBalance; @endphp
                                    @foreach($transactions as $transaction)
                                        @php
                                            $runningBalance += $transaction->transaction_type == 'deposit' 
                                                ? $transaction->amount 
                                                : -$transaction->amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                            <td class="text-success">
                                                @if($transaction->transaction_type == 'deposit')
                                                    ${{ number_format($transaction->amount, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-danger">
                                                @if($transaction->transaction_type == 'withdrawal')
                                                    ${{ number_format($transaction->amount, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-{{ $runningBalance >= 0 ? 'success' : 'danger' }}">
                                                ${{ number_format($runningBalance, 2) }}
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
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No transactions found for the selected date range.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
