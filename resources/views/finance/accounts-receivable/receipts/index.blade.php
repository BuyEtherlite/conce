@extends('layouts.admin')

@section('title', 'Receipts')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>💰 Receipts</h1>
        <a href="{{ route('finance.accounts-receivable.receipts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Receipt
        </a>
    </div>

    <!-- Receipts Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Receipts</h6>
        </div>
        <div class="card-body">
            @if($receipts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Customer</th>
                                <th>Receipt Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $receipt)
                            <tr>
                                <td>
                                    <a href="{{ route('finance.accounts-receivable.receipts.show', $receipt) }}" class="text-decoration-none">
                                        {{ $receipt->receipt_number }}
                                    </a>
                                </td>
                                <td>{{ $receipt->customer->customer_name ?? 'N/A' }}</td>
                                <td>{{ $receipt->receipt_date ? $receipt->receipt_date->format('M d, Y') : 'N/A' }}</td>
                                <td>R {{ number_format($receipt->amount_received, 2) }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($receipt->payment_method ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $receipt->payment_reference ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.accounts-receivable.receipts.show', $receipt) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('finance.accounts-receivable.receipts.edit', $receipt) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $receipts->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No receipts found.</p>
                    <a href="{{ route('finance.accounts-receivable.receipts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Receipt
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
