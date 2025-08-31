@extends('layouts.admin')

@section('title', 'Bills')

@section('page-title', 'Bills Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📄 Bills</h1>
        <a href="{{ route('finance.accounts-payable.bills.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Bill
        </a>
    </div>

    <!-- Bills Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Bills</h6>
        </div>
        <div class="card-body">
            @if($bills->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Bill #</th>
                                <th>Supplier</th>
                                <th>Bill Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Balance Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bills as $bill)
                            <tr>
                                <td>
                                    <a href="{{ route('finance.accounts-payable.bills.show', $bill) }}" class="text-decoration-none">
                                        {{ $bill->bill_number }}
                                    </a>
                                </td>
                                <td>{{ $bill->supplier->supplier_name ?? 'N/A' }}</td>
                                <td>{{ $bill->bill_date ? $bill->bill_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $bill->due_date ? $bill->due_date->format('M d, Y') : 'N/A' }}</td>
                                <td>${{ number_format($bill->bill_amount, 2) }}</td>
                                <td>${{ number_format($bill->balance_due, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'approved' ? 'info' : ($bill->status === 'pending' ? 'warning' : 'danger')) }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.accounts-payable.bills.show', $bill) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($bill->status === 'pending')
                                            <form action="{{ route('finance.accounts-payable.bills.approve', $bill) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to approve this bill?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $bills->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No bills found.</p>
                    <a href="{{ route('finance.accounts-payable.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Bill
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
