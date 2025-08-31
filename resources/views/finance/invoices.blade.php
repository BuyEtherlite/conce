@extends('layouts.app')

@section('page-title', 'Invoices')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Invoices</h4>
        <div>
            <a href="{{ route('finance.create-invoice') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Invoice
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-success">${{ number_format(125000, 2) }}</h5>
                    <small class="text-muted">Total Invoiced This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-info">${{ number_format(95000, 2) }}</h5>
                    <small class="text-muted">Paid This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-warning">${{ number_format(30000, 2) }}</h5>
                    <small class="text-muted">Outstanding</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-primary">{{ 78 }}</h5>
                    <small class="text-muted">Total Invoices</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">All Invoices</h6>
                <div>
                    <select class="form-select form-select-sm d-inline-block w-auto me-2">
                        <option>All Status</option>
                        <option>Draft</option>
                        <option>Sent</option>
                        <option>Paid</option>
                        <option>Overdue</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary">Export</button>
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
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number ?? 'INV-' . str_pad($loop->iteration, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $invoice->customer_name ?? 'Sample Customer ' . $loop->iteration }}</td>
                            <td>{{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : now()->subDays(rand(1, 30))->format('Y-m-d') }}</td>
                            <td>{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->addDays(30)->format('Y-m-d') }}</td>
                            <td>${{ number_format($invoice->amount ?? rand(500, 5000), 2) }}</td>
                            <td>
                                @php
                                    $statuses = ['paid' => 'success', 'sent' => 'primary', 'draft' => 'secondary', 'overdue' => 'danger'];
                                    $status = $invoice->status ?? array_rand($statuses);
                                    $class = $statuses[$status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $class }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary">View</button>
                                    <button class="btn btn-outline-secondary">Edit</button>
                                    <button class="btn btn-outline-success">Send</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
