@extends('layouts.admin')

@section('page-title', 'Accounts Payable')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💳 Accounts Payable</h4>
        <div>
            <a href="{{ route('finance.payables.vendors.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i>New Vendor
            </a>
            <a href="{{ route('finance.payables.bills.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Bill
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['total_vendors'] }}</h3>
                    <small class="text-muted">Total Vendors</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning">{{ $stats['outstanding_bills'] }}</h3>
                    <small class="text-muted">Outstanding Bills</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3 class="text-info">${{ number_format($stats['total_payable'], 2) }}</h3>
                    <small class="text-muted">Total Payable</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3 class="text-danger">{{ $stats['overdue_bills'] }}</h3>
                    <small class="text-muted">Overdue Bills</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-building me-1"></i>Vendors</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage vendor information and relationships</p>
                    <a href="{{ route('finance.accounts-payable.vendors') }}" class="btn btn-primary">View Vendors</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-file-invoice me-1"></i>Bills</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Track and manage vendor bills and invoices</p>
                    <a href="{{ route('finance.accounts-payable.bills') }}" class="btn btn-primary">View Bills</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-money-check me-1"></i>Payments</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Record and track vendor payments</p>
                    <a href="{{ route('finance.accounts-payable.payments') }}" class="btn btn-primary">View Payments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
