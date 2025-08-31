@extends('layouts.app')

@section('title', 'Municipal Bills')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-file-earmark-text"></i> Municipal Bills</h2>
                <a href="{{ route('billing.bills.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Create Bill
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Bills</h5>
                                    <h3>{{ $bills->total() ?? 0 }}</h3>
                                </div>
                                <i class="bi bi-file-earmark-text fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Pending Bills</h5>
                                    <h3>{{ $bills->where('status', 'pending')->count() }}</h3>
                                </div>
                                <i class="bi bi-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Overdue Bills</h5>
                                    <h3>{{ $bills->where('due_date', '<', now())->where('status', '!=', 'paid')->count() }}</h3>
                                </div>
                                <i class="bi bi-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Paid Bills</h5>
                                    <h3>{{ $bills->where('status', 'paid')->count() }}</h3>
                                </div>
                                <i class="bi bi-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search Customer</label>
                            <input type="text" class="form-control" name="customer" value="{{ request('customer') }}" placeholder="Customer name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <a href="{{ route('billing.bills.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bills Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bill Number</th>
                                    <th>Customer</th>
                                    <th>Bill Date</th>
                                    <th>Due Date</th>
                                    <th>Total Amount</th>
                                    <th>Outstanding</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bills ?? [] as $bill)
                                <tr class="{{ $bill->isOverdue() ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $bill->bill_number }}</strong>
                                        @if($bill->billing_period)
                                        <br><small class="text-muted">{{ $bill->billing_period }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $bill->customerAccount->customer_name ?? 'N/A' }}
                                        <br><small class="text-muted">{{ $bill->customerAccount->account_number ?? '' }}</small>
                                    </td>
                                    <td>{{ $bill->bill_date->format('M d, Y') }}</td>
                                    <td>
                                        {{ $bill->due_date->format('M d, Y') }}
                                        @if($bill->isOverdue())
                                        <br><small class="text-danger">{{ $bill->due_date->diffForHumans() }}</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($bill->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $bill->outstanding_amount > 0 ? 'bg-warning' : 'bg-success' }}">
                                            ${{ number_format($bill->outstanding_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $statusClass = match($bill->status) {
                                            'paid' => 'bg-success',
                                            'overdue' => 'bg-danger',
                                            'pending' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('billing.bills.show', $bill->id) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($bill->status !== 'paid')
                                            <a href="{{ route('billing.payments.create', ['bill' => $bill->id]) }}" class="btn btn-outline-success" title="Record Payment">
                                                <i class="bi bi-credit-card"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No bills found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($bills) && $bills->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $bills->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('page-title', 'Bills Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bills Management</h5>
                    <a href="{{ route('billing.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Bill
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Bill #</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No bills found. <a href="{{ route('billing.bills.create') }}">Create your first bill</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
