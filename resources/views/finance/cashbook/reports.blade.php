@extends('layouts.admin')

@section('title', 'Cashbook Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">💰 Cashbook Reports</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.cashbook.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Cashbook
                        </a>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Report Period -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Report Period:</strong> {{ $startDate }} to {{ $endDate }}
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Total Receipts</h5>
                                    <h3>${{ number_format($receipts->sum('amount'), 2) }}</h3>
                                    <small>{{ $receipts->count() }} transactions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5>Total Payments</h5>
                                    <h3>${{ number_format($payments->sum('amount'), 2) }}</h3>
                                    <small>{{ $payments->count() }} transactions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Net Cash Flow</h5>
                                    <h3>${{ number_format($receipts->sum('amount') - $payments->sum('amount'), 2) }}</h3>
                                    <small>{{ $receipts->count() + $payments->count() }} total transactions</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cash Receipts -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Cash Receipts</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($receipts as $receipt)
                                        <tr>
                                            <td>{{ $receipt->transaction_date->format('Y-m-d') }}</td>
                                            <td>{{ $receipt->description }}</td>
                                            <td>{{ $receipt->account->account_name ?? 'N/A' }}</td>
                                            <td>${{ number_format($receipt->amount, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No receipts found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-success">
                                            <th colspan="3">Total Receipts</th>
                                            <th>${{ number_format($receipts->sum('amount'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Cash Payments -->
                        <div class="col-md-6">
                            <h5>Cash Payments</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->transaction_date->format('Y-m-d') }}</td>
                                            <td>{{ $payment->description }}</td>
                                            <td>{{ $payment->account->account_name ?? 'N/A' }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No payments found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-danger">
                                            <th colspan="3">Total Payments</th>
                                            <th>${{ number_format($payments->sum('amount'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filter Form -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Filter Report</h6>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('finance.cashbook.reports') }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="end_date">End Date</label>
                                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-filter"></i> Filter Report
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
