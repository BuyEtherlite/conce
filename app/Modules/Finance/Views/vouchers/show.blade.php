@extends('layouts.admin')

@section('page-title', 'Payment Voucher Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💳 Payment Voucher #{{ $voucher->voucher_number }}</h4>
        <div>
            @if($voucher->status == 'pending')
                <a href="{{ route('finance.vouchers.edit', $voucher->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('finance.vouchers.approve', $voucher->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve this voucher?')">
                        <i class="fas fa-check me-1"></i>Approve
                    </button>
                </form>
            @endif
            
            @if($voucher->status == 'approved')
                <form action="{{ route('finance.vouchers.pay', $voucher->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-info" onclick="return confirm('Mark as paid?')">
                        <i class="fas fa-money-bill me-1"></i>Pay
                    </button>
                </form>
            @endif
            
            <a href="{{ route('finance.vouchers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Vouchers
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Voucher Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Voucher Number:</strong>
                            <p>{{ $voucher->voucher_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong>
                            <p>{{ \Carbon\Carbon::parse($voucher->voucher_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Supplier:</strong>
                            <p>{{ $voucher->supplier->vendor_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Account:</strong>
                            <p>{{ $voucher->account->account_code ?? 'N/A' }} - {{ $voucher->account->account_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Amount:</strong>
                            <p class="h5 text-primary">${{ number_format($voucher->amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Reference Number:</strong>
                            <p>{{ $voucher->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p>{{ $voucher->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status & Tracking</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $voucher->status == 'pending' ? 'warning' : ($voucher->status == 'approved' ? 'success' : ($voucher->status == 'paid' ? 'primary' : 'secondary')) }} fs-6">
                            {{ ucfirst($voucher->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created By:</strong>
                        <p>{{ $voucher->creator->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $voucher->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    
                    @if($voucher->approved_by)
                        <div class="mb-3">
                            <strong>Approved By:</strong>
                            <p>{{ $voucher->approver->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $voucher->approved_at ? \Carbon\Carbon::parse($voucher->approved_at)->format('M d, Y H:i') : 'N/A' }}</small>
                        </div>
                    @endif
                    
                    @if($voucher->paid_by)
                        <div class="mb-3">
                            <strong>Paid By:</strong>
                            <p>{{ $voucher->payer->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $voucher->paid_at ? \Carbon\Carbon::parse($voucher->paid_at)->format('M d, Y H:i') : 'N/A' }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
