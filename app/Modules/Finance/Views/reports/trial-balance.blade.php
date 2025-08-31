@extends('layouts.admin')

@section('title', 'Trial Balance Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>⚖️ Trial Balance Report</h1>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to General Ledger
                    </a>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('finance.reports.trial-balance') }}" class="row align-items-end">
                        <div class="col-md-4">
                            <label for="as_of_date">As of Date</label>
                            <input type="date" class="form-control" id="as_of_date" name="as_of_date" value="{{ $asOfDate }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Trial Balance Report -->
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <h4 class="mb-1">Trial Balance</h4>
                        <p class="mb-0 text-muted">As of {{ date('F d, Y', strtotime($asOfDate)) }}</p>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($trialBalance) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th class="text-end">Debit Balance</th>
                                        <th class="text-end">Credit Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trialBalance as $entry)
                                        <tr>
                                            <td>{{ $entry['account']->account_code }}</td>
                                            <td>{{ $entry['account']->account_name }}</td>
                                            <td class="text-end">
                                                @if($entry['debit_total'] > 0)
                                                    ${{ number_format($entry['debit_total'], 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($entry['credit_total'] > 0)
                                                    ${{ number_format($entry['credit_total'], 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="2" class="text-end">Totals:</th>
                                        <th class="text-end">${{ number_format($totalDebits, 2) }}</th>
                                        <th class="text-end">${{ number_format($totalCredits, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Balance Check -->
                        <div class="mt-3">
                            @if($totalDebits == $totalCredits)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Trial Balance is in balance! Total debits equal total credits.
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Trial Balance is out of balance by ${{ number_format(abs($totalDebits - $totalCredits), 2) }}
                                </div>
                            @endif
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5>Total Accounts</h5>
                                        <h3>{{ count($trialBalance) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>Total Debits</h5>
                                        <h3>${{ number_format($totalDebits, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>Total Credits</h5>
                                        <h3>${{ number_format($totalCredits, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No trial balance data available</h5>
                            <p class="text-muted">No general ledger entries found for the selected date.</p>
                            <a href="{{ route('finance.general-ledger.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Journal Entry
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header .text-center p, .alert, .row.mt-4 {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background: white !important;
        border: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
}
</style>
@endsection
