@extends('layouts.admin')

@section('title', 'Accounts Receivable')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📊 Accounts Receivable</h1>
                <a href="{{ route('finance.accounts-receivable.invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Invoice
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Total Outstanding</h5>
                            <h3>R {{ number_format($stats['total_outstanding'] ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5>Overdue Amount</h5>
                            <h3>R {{ number_format($stats['overdue_amount'] ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>This Month Collected</h5>
                            <h3>R {{ number_format($stats['month_collected'] ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Active Customers</h5>
                            <h3>{{ number_format($stats['active_customers'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link active">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="nav-link">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="nav-link">Customers</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.receipts.index') }}" class="nav-link">Payments</a>
                </li>
            </ul>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_activity ?? [] as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $activity->customer->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($activity->type) }}</td>
                                    <td>R {{ number_format($activity->amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $activity->status == 'paid' ? 'success' : ($activity->status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No recent activity</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection