@extends('layouts.app')

@section('page-title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Invoice #{{ $invoice->invoice_number }}</h4>
        <div>
            <a href="{{ route('finance.invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Invoices
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Invoice Number:</strong>
                            <p>{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer ID:</strong>
                            <p>{{ $invoice->customer_id }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Amount:</strong>
                            <p class="h5 text-success">${{ number_format($invoice->amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Due Date:</strong>
                            <p>{{ $invoice->due_date ? $invoice->due_date->format('F j, Y') : 'Not set' }}</p>
                        </div>
                    </div>
                    @if($invoice->description)
                    <div class="row">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p>{{ $invoice->description }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Created:</strong>
                            <p>{{ $invoice->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <p>{{ $invoice->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.invoices.edit', $invoice) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Invoice
                        </a>
                        <form action="{{ route('finance.invoices.destroy', $invoice) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                <i class="fas fa-trash me-1"></i>Delete Invoice
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
