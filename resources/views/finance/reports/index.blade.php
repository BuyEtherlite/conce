@extends('layouts.admin')

@section('title', 'Financial Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">📊 Financial Reports</h3>
                    <a href="{{ route('finance.reports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Generate Report
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5><a href="{{ route('finance.reports.balance-sheet') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-balance-scale"></i> Balance Sheet
                                    </a></h5>
                                    <p class="card-text">Assets, Liabilities & Equity</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5><a href="{{ route('finance.reports.income-statement') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-chart-line"></i> Income Statement
                                    </a></h5>
                                    <p class="card-text">Revenue & Expenses</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5><a href="{{ route('finance.reports.cash-flow') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-money-bill-wave"></i> Cash Flow
                                    </a></h5>
                                    <p class="card-text">Cash Inflows & Outflows</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5><a href="{{ route('finance.reports.trial-balance') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-calculator"></i> Trial Balance
                                    </a></h5>
                                    <p class="card-text">Account Balances</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->report_name }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst(str_replace('_', ' ', $report->report_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->report_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $report->status === 'generated' ? 'success' : 'warning' }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('finance.reports.show', $report) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No reports found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection