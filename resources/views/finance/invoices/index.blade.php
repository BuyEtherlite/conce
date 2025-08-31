@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoices</h4>
                    <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Invoice
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $invoice->status_badge }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('finance.invoices.show', $invoice) }}" 
                                                       class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="{{ route('finance.invoices.edit', $invoice) }}" 
                                                       class="btn btn-sm btn-outline-secondary">Edit</a>
                                                    <form action="{{ route('finance.invoices.destroy', $invoice) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5>No invoices found</h5>
                            <p class="text-muted">Start by creating your first invoice</p>
                            <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary">
                                Create Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
