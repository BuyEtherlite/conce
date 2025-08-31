@extends('layouts.admin')

@section('title', 'Receipt Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>💰 Receipt #{{ $receipt->receipt_number }}</h1>
        <div>
            <a href="{{ route('finance.accounts-receivable.receipts.edit', $receipt) }}" class="btn btn-secondary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('finance.accounts-receivable.receipts.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Receipts
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Receipt Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Receipt Number:</th>
                                    <td>{{ $receipt->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <th>Customer:</th>
                                    <td>{{ $receipt->customer->customer_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Receipt Date:</th>
                                    <td>{{ $receipt->receipt_date ? $receipt->receipt_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount Received:</th>
                                    <td><strong>R {{ number_format($receipt->amount_received, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ ucfirst($receipt->payment_method ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Reference:</th>
                                    <td>{{ $receipt->payment_reference ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bank Account:</th>
                                    <td>{{ $receipt->bank_account ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $receipt->created_at ? $receipt->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($receipt->notes)
                    <div class="mt-3">
                        <h6>Notes:</h6>
                        <p class="text-muted">{{ $receipt->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.accounts-receivable.receipts.edit', $receipt) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Receipt
                        </a>
                        <button class="btn btn-outline-info" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <a href="{{ route('finance.accounts-receivable.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-chart-line"></i> AR Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
