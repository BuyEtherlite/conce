@extends('layouts.admin')

@section('page-title', 'Statement of Changes in Net Assets/Equity')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">📊 Statement of Changes in Net Assets/Equity</h1>
            <p class="text-muted">IPSAS Compliant Financial Statement</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('finance.ipsas-reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.ipsas-reports.statement-changes-net-assets') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="currency" class="form-label">Currency</label>
                        <select class="form-control" id="currency" name="currency">
                            <option value="ZWL" {{ $currency == 'ZWL' ? 'selected' : '' }}>Zimbabwe Dollar (ZWL)</option>
                            <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="RTGS" {{ $currency == 'RTGS' ? 'selected' : '' }}>RTGS Dollar</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statement -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Statement of Changes in Net Assets/Equity</h5>
            <small>For the period {{ $startDate }} to {{ $endDate }} ({{ $currency }})</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Accumulated Surplus</th>
                            <th class="text-end">Reserves</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Opening Balance as at {{ $startDate }}</strong></td>
                            <td class="text-end">{{ number_format($openingAccumulatedSurplus, 2) }}</td>
                            <td class="text-end">{{ number_format($openingReserves, 2) }}</td>
                            <td class="text-end"><strong>{{ number_format($openingAccumulatedSurplus + $openingReserves, 2) }}</strong></td>
                        </tr>
                        
                        <tr>
                            <td>Net Surplus/(Deficit) for the period</td>
                            <td class="text-end">{{ number_format($surplusForPeriod, 2) }}</td>
                            <td class="text-end">-</td>
                            <td class="text-end">{{ number_format($surplusForPeriod, 2) }}</td>
                        </tr>
                        
                        <tr>
                            <td>Transfers to Reserves</td>
                            <td class="text-end">({{ number_format($transfersToReserves, 2) }})</td>
                            <td class="text-end">{{ number_format($transfersToReserves, 2) }}</td>
                            <td class="text-end">-</td>
                        </tr>
                        
                        @if($otherMovements != 0)
                        <tr>
                            <td>Other Movements</td>
                            <td class="text-end">{{ number_format($otherMovements, 2) }}</td>
                            <td class="text-end">-</td>
                            <td class="text-end">{{ number_format($otherMovements, 2) }}</td>
                        </tr>
                        @endif
                        
                        <tr class="table-primary">
                            <td><strong>Closing Balance as at {{ $endDate }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($closingAccumulatedSurplus, 2) }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($closingReserves, 2) }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($closingAccumulatedSurplus + $closingReserves, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Notes</h6>
        </div>
        <div class="card-body">
            <ol>
                <li>This statement shows changes in net assets/equity during the reporting period.</li>
                <li>Net surplus/(deficit) represents the excess of revenues over expenses for the period.</li>
                <li>Transfers to reserves represent amounts set aside for specific purposes.</li>
                <li>All amounts are presented in {{ $currency }}.</li>
                <li>This statement complies with IPSAS requirements for public sector entities.</li>
            </ol>
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    // Implementation for Excel export
    alert('Excel export functionality to be implemented');
}
</script>
@endsection
@extends('layouts.app')

@section('title', 'Statement of Changes in Net Assets')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statement of Changes in Net Assets</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Period:</strong> Year Ended {{ date('F j, Y') }}
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Currency:</strong> USD
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-right">Net Assets Without Donor Restrictions</th>
                                    <th class="text-right">Net Assets With Donor Restrictions</th>
                                    <th class="text-right">Total Net Assets</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Net assets at beginning of year</strong></td>
                                    <td class="text-right">{{ number_format(1000000, 2) }}</td>
                                    <td class="text-right">{{ number_format(500000, 2) }}</td>
                                    <td class="text-right">{{ number_format(1500000, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Surplus/(Deficit) for the year</td>
                                    <td class="text-right">{{ number_format(150000, 2) }}</td>
                                    <td class="text-right">{{ number_format(0, 2) }}</td>
                                    <td class="text-right">{{ number_format(150000, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Net assets released from restrictions</td>
                                    <td class="text-right">{{ number_format(50000, 2) }}</td>
                                    <td class="text-right">{{ number_format(-50000, 2) }}</td>
                                    <td class="text-right">{{ number_format(0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Other comprehensive income</td>
                                    <td class="text-right">{{ number_format(25000, 2) }}</td>
                                    <td class="text-right">{{ number_format(0, 2) }}</td>
                                    <td class="text-right">{{ number_format(25000, 2) }}</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td><strong>Net assets at end of year</strong></td>
                                    <td class="text-right">{{ number_format(1225000, 2) }}</td>
                                    <td class="text-right">{{ number_format(450000, 2) }}</td>
                                    <td class="text-right">{{ number_format(1675000, 2) }}</td>
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
