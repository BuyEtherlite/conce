@extends('layouts.app')

@section('page-title', 'Accounts Receivable')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📋 Accounts Receivable</h4>
        <div>
            <a href="{{ route('finance.accounts-receivable.invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Invoice
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="text-primary">${{ number_format(250000, 2) }}</h5>
                    <small class="text-muted">Total Outstanding</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="text-success">${{ number_format(180000, 2) }}</h5>
                    <small class="text-muted">Current (0-30 days)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="text-warning">${{ number_format(50000, 2) }}</h5>
                    <small class="text-muted">Past Due (31-60 days)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h5 class="text-danger">${{ number_format(20000, 2) }}</h5>
                    <small class="text-muted">Overdue (60+ days)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Outstanding Invoices</h6>
                <div>
                    <button class="btn btn-sm btn-outline-primary">Export</button>
                    <button class="btn btn-sm btn-outline-secondary">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Days Overdue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receivables as $receivable)
                        <tr>
                            <td>{{ $receivable->invoice_number }}</td>
                            <td>{{ $receivable->customer_name }}</td>
                            <td>{{ $receivable->issue_date->format('Y-m-d') }}</td>
                            <td>{{ $receivable->due_date->format('Y-m-d') }}</td>
                            <td>${{ number_format($receivable->total_amount, 2) }}</td>
                            <td>${{ number_format($receivable->paid_amount, 2) }}</td>
                            <td>${{ number_format($receivable->balance_due, 2) }}</td>
                            <td>
                                @php
                                    $overdue = $receivable->due_date->diffInDays(now(), false);
                                @endphp
                                {{ $overdue > 0 ? $overdue : 0 }}
                            </td>
                            <td>
                                @if($receivable->balance_due <= 0)
                                    <span class="badge bg-success">Paid</span>
                                @elseif($overdue > 60)
                                    <span class="badge bg-danger">Overdue</span>
                                @elseif($overdue > 30)
                                    <span class="badge bg-warning">Past Due</span>
                                @else
                                    <span class="badge bg-primary">Current</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-success">Collect</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No outstanding invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
