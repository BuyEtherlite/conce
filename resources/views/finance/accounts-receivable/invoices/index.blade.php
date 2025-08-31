@extends('layouts.admin')

@section('title', 'AR Invoices')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📄 AR Invoices</h1>
                <a href="{{ route('finance.accounts-receivable.invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Invoice
                </a>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="nav-link active">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="nav-link">Customers</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.receipts.index') }}" class="nav-link">Payments</a>
                </li>
            </ul>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Balance Due</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->customer->display_name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->balance_due, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status_color ?? 'secondary' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('finance.accounts-receivable.invoices.show', $invoice) }}" class="btn btn-outline-primary">View</a>
                                            <a href="#" class="btn btn-outline-success">Print</a>
                                            @if($invoice->status != 'paid')
                                            <form method="POST" action="{{ route('finance.accounts-receivable.mark-as-paid', $invoice) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Mark as paid?')">Mark Paid</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
