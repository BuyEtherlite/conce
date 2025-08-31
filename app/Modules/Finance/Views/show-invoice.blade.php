@extends('layouts.app')

@section('page-title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Invoice {{ $invoice->invoice_number }}</h4>
        <div>
            <a href="{{ route('finance.invoices') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Back to Invoices
            </a>
            @if($invoice->status !== 'paid')
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="fas fa-check me-1"></i>Mark as Paid
            </button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice Details</h5>
                    <span class="badge bg-{{ $invoice->status_color }} fs-6">{{ ucfirst($invoice->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Bill To:</h6>
                            <strong>{{ $invoice->customer_name }}</strong><br>
                            {{ $invoice->customer_email }}<br>
                            @if($invoice->customer_phone)
                                {{ $invoice->customer_phone }}<br>
                            @endif
                            @if($invoice->customer_address)
                                {{ $invoice->customer_address }}
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="text-muted">Invoice Details:</h6>
                            <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                            <strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                            <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                            @if($invoice->paid_at)
                                <strong>Paid Date:</strong> {{ $invoice->paid_at->format('M d, Y') }}<br>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Description:</h6>
                        <p>{{ $invoice->description }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">R{{ number_format($invoice->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax:</strong></td>
                                    <td class="text-end">R{{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>R{{ number_format($invoice->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($invoice->notes)
                    <div class="mt-4">
                        <h6 class="text-muted">Notes:</h6>
                        <p>{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Council</small>
                        <div>{{ $invoice->council->name ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Department</small>
                        <div>{{ $invoice->department->name ?? 'N/A' }}</div>
                    </div>
                    @if($invoice->payment_method)
                    <div class="mb-3">
                        <small class="text-muted">Payment Method</small>
                        <div>{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</div>
                    </div>
                    @endif
                    @if($invoice->payment_reference)
                    <div class="mb-3">
                        <small class="text-muted">Payment Reference</small>
                        <div>{{ $invoice->payment_reference }}</div>
                    </div>
                    @endif
                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <div>{{ $invoice->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    @if($invoice->updated_at != $invoice->created_at)
                    <div class="mb-3">
                        <small class="text-muted">Last Updated</small>
                        <div>{{ $invoice->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            @if($invoice->payments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="card-body">
                    @foreach($invoice->payments as $payment)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <strong>R{{ number_format($payment->amount, 2) }}</strong>
                            <small class="text-muted">{{ $payment->payment_date->format('M d, Y') }}</small>
                        </div>
                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</small>
                        @if($payment->reference_number)
                        <br><small class="text-muted">Ref: {{ $payment->reference_number }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($invoice->status !== 'paid')
<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Invoice as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('finance.mark-as-paid', $invoice) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Invoice</label>
                        <input type="text" class="form-control" value="{{ $invoice->invoice_number }} - R{{ number_format($invoice->total_amount, 2) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Reference (Optional)</label>
                        <input type="text" name="payment_reference" class="form-control" placeholder="Transaction ID, cheque number, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
