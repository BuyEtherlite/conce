@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Accounts Receivable Aging Report</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.debtors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Debtors
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Current (0-30 days)</h5>
                    <h3>${{ number_format(collect($agingData)->sum('current'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">31-60 days</h5>
                    <h3>${{ number_format(collect($agingData)->sum('30_days'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">61-90 days</h5>
                    <h3>${{ number_format(collect($agingData)->sum('60_days'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Over 90 days</h5>
                    <h3>${{ number_format(collect($agingData)->sum('over_90'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Aging Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detailed Aging Report</h4>
                    <div class="card-header-actions">
                        <button class="btn btn-primary btn-sm" onclick="printReport()">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="agingTable">
                            <thead>
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th class="text-right">Current (0-30)</th>
                                    <th class="text-right">31-60 Days</th>
                                    <th class="text-right">61-90 Days</th>
                                    <th class="text-right">Over 90 Days</th>
                                    <th class="text-right">Total Outstanding</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agingData as $aging)
                                <tr>
                                    <td><strong>{{ $aging['customer']->customer_code ?? 'N/A' }}</strong></td>
                                    <td>{{ $aging['customer']->company_name ?? $aging['customer']->first_name . ' ' . $aging['customer']->last_name }}</td>
                                    <td>{{ $aging['customer']->email }}</td>
                                    <td>{{ $aging['customer']->phone }}</td>
                                    <td class="text-right">
                                        @if($aging['current'] > 0)
                                            <span class="badge badge-success">${{ number_format($aging['current'], 2) }}</span>
                                        @else
                                            <span class="text-muted">$0.00</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($aging['30_days'] > 0)
                                            <span class="badge badge-warning">${{ number_format($aging['30_days'], 2) }}</span>
                                        @else
                                            <span class="text-muted">$0.00</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($aging['60_days'] > 0)
                                            <span class="badge badge-danger">${{ number_format($aging['60_days'], 2) }}</span>
                                        @else
                                            <span class="text-muted">$0.00</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($aging['over_90'] > 0)
                                            <span class="badge badge-dark">${{ number_format($aging['over_90'], 2) }}</span>
                                        @else
                                            <span class="text-muted">$0.00</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>${{ number_format($aging['total'], 2) }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('finance.debtors.show', $aging['customer']->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('finance.debtors.customer-statement', $aging['customer']->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="View Statement">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-warning" 
                                                    onclick="sendReminder({{ $aging['customer']->id }})" title="Send Reminder">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No overdue accounts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4">TOTALS</td>
                                    <td class="text-right">${{ number_format(collect($agingData)->sum('current'), 2) }}</td>
                                    <td class="text-right">${{ number_format(collect($agingData)->sum('30_days'), 2) }}</td>
                                    <td class="text-right">${{ number_format(collect($agingData)->sum('60_days'), 2) }}</td>
                                    <td class="text-right">${{ number_format(collect($agingData)->sum('over_90'), 2) }}</td>
                                    <td class="text-right"><strong>${{ number_format(collect($agingData)->sum('total'), 2) }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Report Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('finance.debtors.aging-report') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="as_of_date">As of Date</label>
                                    <input type="date" class="form-control" id="as_of_date" name="as_of_date" 
                                           value="{{ request('as_of_date', now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_amount">Minimum Amount</label>
                                    <input type="number" class="form-control" id="min_amount" name="min_amount" 
                                           value="{{ request('min_amount', 0) }}" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer_search">Customer Search</label>
                                    <input type="text" class="form-control" id="customer_search" name="customer_search" 
                                           value="{{ request('customer_search') }}" placeholder="Name or Code">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('finance.debtors.aging-report') }}" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    window.print();
}

function exportToExcel() {
    // You can implement Excel export functionality here
    alert('Excel export functionality to be implemented');
}

function sendReminder(customerId) {
    // You can implement email reminder functionality here
    alert('Reminder functionality to be implemented for customer ID: ' + customerId);
}

// Initialize DataTable if available
$(document).ready(function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#agingTable').DataTable({
            "pageLength": 25,
            "order": [[ 8, "desc" ]], // Sort by total outstanding
            "columnDefs": [
                { "orderable": false, "targets": 9 } // Actions column
            ]
        });
    }
});
</script>

<style>
@media print {
    .page-title-right,
    .card-header-actions,
    .btn-group,
    .card:last-child {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}

.badge {
    font-size: 0.85em;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.table tfoot tr {
    border-top: 2px solid #dee2e6;
}
</style>
@endsection
