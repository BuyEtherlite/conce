@extends('layouts.admin')

@section('page-title', 'Water Billing')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Water Billing</h4>
        <a href="{{ route('water.billing.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Generate Bills
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Water Bills</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bill Number</th>
                            <th>Customer</th>
                            <th>Bill Date</th>
                            <th>Due Date</th>
                            <th>Consumption</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                        <tr>
                            <td>{{ $bill->bill_number }}</td>
                            <td>{{ $bill->connection->customer_name ?? 'N/A' }}</td>
                            <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                            <td>{{ $bill->due_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($bill->consumption, 2) }} m³</td>
                            <td>{{ number_format($bill->total_amount, 2) }}</td>
                            <td>
                                @if($bill->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($bill->status === 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">Print</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-receipt fa-3x mb-3"></i>
                                    <p>No bills found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($bills->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $bills->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
