@extends('layouts.admin')

@section('title', 'Finance Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Finance Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('finance.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.chart-of-accounts.index') }}">
                            <i class="fas fa-chart-line"></i> Chart of Accounts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.general-ledger.index') }}">
                            <i class="fas fa-book"></i> General Ledger
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.accounts-receivable.index') }}">
                            <i class="fas fa-money-bill-wave"></i> Accounts Receivable
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.accounts-payable.index') }}">
                            <i class="fas fa-credit-card"></i> Accounts Payable
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.budgets.index') }}">
                            <i class="fas fa-calculator"></i> Budgets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.reports.index') }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Finance Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('finance.general-ledger.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> New Transaction
                    </a>
                    <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Assets
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['total_assets'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-wallet2 fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Monthly Revenue
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['monthly_revenue'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-graph-up-arrow fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Cash Balance
                                    </div>
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['cash_balance'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Budget Utilization
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ number_format($stats['budget_utilization'], 1) }}%
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                     style="width: {{ min($stats['budget_utilization'], 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-pie-chart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row Stats -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Accounts Receivable
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['accounts_receivable'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-receipt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                        Accounts Payable
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['accounts_payable'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-credit-card fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-dark shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Monthly Expenses
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($stats['monthly_expenses'], 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-graph-down-arrow fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Transactions -->
                <div class="col-xl-6 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                            <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Account</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_date->format('M d') }}</td>
                                            <td>
                                                <small>{{ $transaction->account->account_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($transaction->description, 30) }}</small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-{{ $transaction->transaction_type === 'debit' ? 'danger' : 'success' }}">
                                                    {{ $transaction->transaction_type === 'debit' ? '-' : '+' }}${{ $transaction->formatted_amount }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No recent transactions</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Invoices -->
                <div class="col-xl-6 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Outstanding Invoices</h6>
                            <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($outstanding_invoices as $invoice)
                                        <tr>
                                            <td>
                                                <small>{{ $invoice->invoice_number }}</small>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($invoice->customer->customer_name ?? 'N/A', 20) }}</small>
                                            </td>
                                            <td>
                                                <small class="{{ $invoice->due_date < now() ? 'text-danger' : '' }}">
                                                    {{ $invoice->due_date->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <small>${{ number_format($invoice->balance_due, 2) }}</small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No outstanding invoices</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Comparison -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Budget vs Actual (Current Month)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Budgeted
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($budget_comparison['budgeted'], 2) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Actual
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($budget_comparison['actual'], 2) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs font-weight-bold text-{{ $budget_comparison['variance'] >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                                        Variance
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${{ number_format($budget_comparison['variance'], 2) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        % Used
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($budget_comparison['percentage'], 1) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $budget_comparison['percentage'] > 100 ? 'danger' : ($budget_comparison['percentage'] > 80 ? 'warning' : 'success') }}"
                                         role="progressbar"
                                         style="width: {{ min($budget_comparison['percentage'], 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add any specific dashboard functionality here
});
</script>
@endpush