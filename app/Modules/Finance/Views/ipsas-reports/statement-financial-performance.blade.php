@extends('layouts.admin')

@section('title', 'Statement of Financial Performance - IPSAS Compliant')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Statement of Financial Performance</h1>
        <div class="d-flex">
            <form method="GET" class="d-flex me-3">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control me-2" placeholder="Start Date">
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control me-2" placeholder="End Date">
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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Statement of Financial Performance</h5>
            <small>For the period from {{ $startDate }} to {{ $endDate }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Account</th>
                            <th class="text-end">Amount ({{ $currency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Revenue from Exchange Transactions -->
                        <tr class="table-info">
                            <td><strong>REVENUE FROM EXCHANGE TRANSACTIONS</strong></td>
                            <td></td>
                        </tr>
                        @foreach($exchangeRevenue as $revenue)
                        <tr>
                            <td class="ps-4">{{ $revenue->account_code }}</td>
                            <td class="text-end">{{ number_format($revenue->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Revenue from Exchange Transactions</strong></td>
                            <td class="text-end"><strong>{{ number_format($exchangeRevenue->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Revenue from Non-Exchange Transactions -->
                        <tr class="table-info">
                            <td><strong>REVENUE FROM NON-EXCHANGE TRANSACTIONS</strong></td>
                            <td></td>
                        </tr>
                        @foreach($nonExchangeRevenue as $revenue)
                        <tr>
                            <td class="ps-4">{{ $revenue->account_code }}</td>
                            <td class="text-end">{{ number_format($revenue->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Revenue from Non-Exchange Transactions</strong></td>
                            <td class="text-end"><strong>{{ number_format($nonExchangeRevenue->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Total Revenue -->
                        <tr class="table-success">
                            <td><strong>TOTAL REVENUE</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalRevenue, 2) }}</strong></td>
                        </tr>

                        <!-- Expenses -->
                        <tr class="table-warning">
                            <td><strong>EXPENSES</strong></td>
                            <td></td>
                        </tr>

                        <!-- Employee Costs -->
                        <tr class="table-light">
                            <td><strong>Employee Costs</strong></td>
                            <td></td>
                        </tr>
                        @foreach($employeeCosts as $expense)
                        <tr>
                            <td class="ps-4">{{ $expense->account_code }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Employee Costs</strong></td>
                            <td class="text-end"><strong>{{ number_format($employeeCosts->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Goods and Services -->
                        <tr class="table-light">
                            <td><strong>Goods and Services</strong></td>
                            <td></td>
                        </tr>
                        @foreach($goodsAndServices as $expense)
                        <tr>
                            <td class="ps-4">{{ $expense->account_code }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Goods and Services</strong></td>
                            <td class="text-end"><strong>{{ number_format($goodsAndServices->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Depreciation -->
                        <tr class="table-light">
                            <td><strong>Depreciation</strong></td>
                            <td></td>
                        </tr>
                        @foreach($depreciation as $expense)
                        <tr>
                            <td class="ps-4">{{ $expense->account_code }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Depreciation</strong></td>
                            <td class="text-end"><strong>{{ number_format($depreciation->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Other Expenses -->
                        <tr class="table-light">
                            <td><strong>Other Expenses</strong></td>
                            <td></td>
                        </tr>
                        @foreach($otherExpenses as $expense)
                        <tr>
                            <td class="ps-4">{{ $expense->account_code }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>Total Other Expenses</strong></td>
                            <td class="text-end"><strong>{{ number_format($otherExpenses->sum('amount'), 2) }}</strong></td>
                        </tr>

                        <!-- Total Expenses -->
                        <tr class="table-danger">
                            <td><strong>TOTAL EXPENSES</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalExpenses, 2) }}</strong></td>
                        </tr>

                        <!-- Net Surplus/Deficit -->
                        <tr class="table-primary">
                            <td><strong>NET SURPLUS/(DEFICIT) FOR THE PERIOD</strong></td>
                            <td class="text-end">
                                <strong class="{{ $netSurplusDeficit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($netSurplusDeficit, 2) }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Financial Performance Summary</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><strong>Total Revenue:</strong> {{ number_format($totalRevenue, 2) }} {{ $currency }}</li>
                                <li><strong>Total Expenses:</strong> {{ number_format($totalExpenses, 2) }} {{ $currency }}</li>
                                <li><strong>Net Result:</strong> 
                                    <span class="{{ $netSurplusDeficit >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($netSurplusDeficit, 2) }} {{ $currency }}
                                    </span>
                                </li>
                                <li><strong>Performance Ratio:</strong> 
                                    {{ $totalExpenses > 0 ? number_format(($totalRevenue / $totalExpenses) * 100, 2) : '0.00' }}%
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">IPSAS Compliance Notes</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li>• Revenue recognized per IPSAS 9 and 23</li>
                                <li>• Expenses classified by nature per IPSAS 1</li>
                                <li>• Exchange vs non-exchange distinction maintained</li>
                                <li>• Depreciation calculated per IPSAS 17</li>
                                <li>• Employee benefits per IPSAS 39</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .form-control, .form-select {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
