@extends('layouts.admin')

@section('page-title', 'Voucher Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📊 Voucher Reports</h4>
        <a href="{{ route('finance.vouchers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Vouchers
        </a>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.vouchers.reports') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        @foreach($statusSummary as $status => $data)
            <div class="col-md-3">
                <div class="card text-white bg-{{ $status == 'pending' ? 'warning' : ($status == 'approved' ? 'success' : ($status == 'paid' ? 'primary' : 'secondary')) }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ ucfirst($status) }}</div>
                                <div class="h6 mb-0">{{ $data['count'] }} vouchers</div>
                                <div class="h5 mb-0">${{ number_format($data['total'], 2) }}</div>
                            </div>
                            <div class="fas fa-file-invoice fa-2x"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detailed Report -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Voucher Details</h5>
        </div>
        <div class="card-body">
            @if($vouchers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Voucher #</th>
                                <th>Supplier</th>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($voucher->voucher_date)->format('M d, Y') }}</td>
                                    <td>{{ $voucher->voucher_number }}</td>
                                    <td>{{ $voucher->supplier->vendor_name ?? 'N/A' }}</td>
                                    <td>{{ $voucher->account->account_name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($voucher->description, 50) }}</td>
                                    <td>${{ number_format($voucher->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $voucher->status == 'pending' ? 'warning' : ($voucher->status == 'approved' ? 'success' : ($voucher->status == 'paid' ? 'primary' : 'secondary')) }}">
                                            {{ ucfirst($voucher->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <th colspan="5">Total</th>
                                <th>${{ number_format($vouchers->sum('amount'), 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <h5>No vouchers found for the selected period</h5>
                    <p class="text-muted">Try adjusting the date range</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
