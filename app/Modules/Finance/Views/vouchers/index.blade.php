@extends('layouts.admin')

@section('page-title', 'Payment Vouchers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💳 Payment Vouchers</h4>
        <div>
            <a href="{{ route('finance.vouchers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Voucher
            </a>
            <a href="{{ route('finance.vouchers.reports') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-bar me-1"></i>Reports
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Voucher List</h5>
        </div>
        <div class="card-body">
            @if($vouchers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Voucher #</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->voucher_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->voucher_date)->format('M d, Y') }}</td>
                                    <td>{{ $voucher->supplier->vendor_name ?? 'N/A' }}</td>
                                    <td>{{ $voucher->account->account_name ?? 'N/A' }}</td>
                                    <td>${{ number_format($voucher->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $voucher->status == 'pending' ? 'warning' : ($voucher->status == 'approved' ? 'success' : ($voucher->status == 'paid' ? 'primary' : 'secondary')) }}">
                                            {{ ucfirst($voucher->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('finance.vouchers.show', $voucher->id) }}" 
                                               class="btn btn-sm btn-outline-primary">View</a>
                                            
                                            @if($voucher->status == 'pending')
                                                <a href="{{ route('finance.vouchers.edit', $voucher->id) }}" 
                                                   class="btn btn-sm btn-outline-secondary">Edit</a>
                                                <form action="{{ route('finance.vouchers.approve', $voucher->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                            onclick="return confirm('Approve this voucher?')">Approve</button>
                                                </form>
                                            @endif
                                            
                                            @if($voucher->status == 'approved')
                                                <form action="{{ route('finance.vouchers.pay', $voucher->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-info"
                                                            onclick="return confirm('Mark as paid?')">Pay</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $vouchers->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                    <h5>No payment vouchers found</h5>
                    <p class="text-muted">Start by creating your first payment voucher</p>
                    <a href="{{ route('finance.vouchers.create') }}" class="btn btn-primary">
                        Create Payment Voucher
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
