@extends('layouts.admin')

@section('title', 'AR Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>💳 AR Payments</h1>
                <a href="{{ route('finance.accounts-receivable.receipts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Record Payment
                </a>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="nav-link">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="nav-link">Customers</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.payments') }}" class="nav-link active">Payments</a>
                </li>
            </ul>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Reference</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                <tr>
                                    <td>{{ $receipt->receipt_number ?? 'N/A' }}</td>
                                    <td>{{ $receipt->customer->display_name ?? 'N/A' }}</td>
                                    <td>{{ $receipt->receipt_date ? $receipt->receipt_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>${{ number_format($receipt->amount_received ?? 0, 2) }}</td>
                                    <td>{{ ucfirst($receipt->payment_method ?? 'N/A') }}</td>
                                    <td>{{ $receipt->reference_number ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary">View</a>
                                            <a href="#" class="btn btn-outline-success">Print</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No payments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $receipts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
