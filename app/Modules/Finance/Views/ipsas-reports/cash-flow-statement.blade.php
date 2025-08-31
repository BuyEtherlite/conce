@extends('layouts.admin')

@section('title', 'Cash Flow Statement - IPSAS Compliant')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cash Flow Statement</h1>
        <div class="d-flex">
            <form method="GET" class="d-flex me-3">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control me-2">
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control me-2">
                <select name="currency" class="form-select me-2">
                    <option value="ZWL" {{ $currency == 'ZWL' ? 'selected' : '' }}>ZWL</option>
                    <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="ZAR" {{ $currency == 'ZAR' ? 'selected' : '' }}>ZAR</option>
                </select>
                <button type="submit" class="btn btn-primary">Generate</button>
            </form>
            <a href="#" onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Cash Flow Statement for the period {{ $startDate }} to {{ $endDate }}
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Cash Flow Activities</th>
                            <th class="text-right">{{ $currency }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Operating Activities -->
                        <tr class="table-info">
                            <td><strong>CASH FLOWS FROM OPERATING ACTIVITIES</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash receipts from operations</td>
                            <td class="text-right">{{ number_format($operatingReceipts, 2) }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash payments for operations</td>
                            <td class="text-right">({{ number_format($operatingPayments, 2) }})</td>
                        </tr>
                        <tr class="table-secondary">
                            <td><strong>&nbsp;&nbsp;Net cash from operating activities</strong></td>
                            <td class="text-right"><strong>{{ number_format($netOperatingCash, 2) }}</strong></td>
                        </tr>

                        <!-- Investing Activities -->
                        <tr class="table-info">
                            <td><strong>CASH FLOWS FROM INVESTING ACTIVITIES</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash receipts from investing activities</td>
                            <td class="text-right">{{ number_format($investingReceipts, 2) }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash payments for investing activities</td>
                            <td class="text-right">({{ number_format($investingPayments, 2) }})</td>
                        </tr>
                        <tr class="table-secondary">
                            <td><strong>&nbsp;&nbsp;Net cash from investing activities</strong></td>
                            <td class="text-right"><strong>{{ number_format($netInvestingCash, 2) }}</strong></td>
                        </tr>

                        <!-- Financing Activities -->
                        <tr class="table-info">
                            <td><strong>CASH FLOWS FROM FINANCING ACTIVITIES</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash receipts from financing activities</td>
                            <td class="text-right">{{ number_format($financingReceipts, 2) }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;Cash payments for financing activities</td>
                            <td class="text-right">({{ number_format($financingPayments, 2) }})</td>
                        </tr>
                        <tr class="table-secondary">
                            <td><strong>&nbsp;&nbsp;Net cash from financing activities</strong></td>
                            <td class="text-right"><strong>{{ number_format($netFinancingCash, 2) }}</strong></td>
                        </tr>

                        <!-- Net Cash Movement -->
                        <tr class="table-warning">
                            <td><strong>NET INCREASE/(DECREASE) IN CASH</strong></td>
                            <td class="text-right"><strong>{{ number_format($netCashMovement, 2) }}</strong></td>
                        </tr>

                        <!-- Cash Balances -->
                        <tr>
                            <td><strong>Cash at beginning of period</strong></td>
                            <td class="text-right"><strong>{{ number_format($openingCash, 2) }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>CASH AT END OF PERIOD</strong></td>
                            <td class="text-right"><strong>{{ number_format($closingCash, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes Section -->
            <div class="mt-4">
                <h6 class="font-weight-bold">Notes:</h6>
                <ul class="list-unstyled">
                    <li>1. This cash flow statement has been prepared in accordance with IPSAS 2 - Cash Flow Statements.</li>
                    <li>2. Cash flows are classified into operating, investing, and financing activities.</li>
                    <li>3. Operating activities are the principal revenue-producing activities of the entity.</li>
                    <li>4. Investing activities are the acquisition and disposal of long-term assets.</li>
                    <li>5. Financing activities result in changes in the size and composition of equity and borrowings.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, .d-sm-flex {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'Cash Flow Statement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cash Flow Statement</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Period:</strong> {{ date('F j, Y', strtotime($startDate)) }} to {{ date('F j, Y', strtotime($endDate)) }}
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Currency:</strong> {{ $currency }}
                        </div>
                    </div>

                    <!-- Operating Activities -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2"><strong>Cash Flows from Operating Activities</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cash receipts from operating activities</td>
                                    <td class="text-right">{{ number_format($operatingReceipts, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Cash payments for operating activities</td>
                                    <td class="text-right">({{ number_format($operatingPayments, 2) }})</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Net cash from operating activities</td>
                                    <td class="text-right">{{ number_format($netOperatingCash, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Investing Activities -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2"><strong>Cash Flows from Investing Activities</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cash receipts from investing activities</td>
                                    <td class="text-right">{{ number_format($investingReceipts, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Cash payments for investing activities</td>
                                    <td class="text-right">({{ number_format($investingPayments, 2) }})</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Net cash from investing activities</td>
                                    <td class="text-right">{{ number_format($netInvestingCash, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Financing Activities -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2"><strong>Cash Flows from Financing Activities</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cash receipts from financing activities</td>
                                    <td class="text-right">{{ number_format($financingReceipts, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Cash payments for financing activities</td>
                                    <td class="text-right">({{ number_format($financingPayments, 2) }})</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Net cash from financing activities</td>
                                    <td class="text-right">{{ number_format($netFinancingCash, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Net Change in Cash -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2"><strong>Net Change in Cash and Cash Equivalents</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Net increase (decrease) in cash</td>
                                    <td class="text-right">{{ number_format($netCashMovement, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Cash at beginning of period</td>
                                    <td class="text-right">{{ number_format($openingCash, 2) }}</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Cash at end of period</td>
                                    <td class="text-right">{{ number_format($closingCash, 2) }}</td>
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
