@extends('layouts.admin')

@section('title', 'Statement of Financial Position - IPSAS Compliant')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Statement of Financial Position</h1>
        <div class="d-flex">
            <form method="GET" class="d-flex me-3">
                <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="form-control me-2">
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

    <div class="card shadow">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="h4 mb-1">STATEMENT OF FINANCIAL POSITION</h2>
                <h3 class="h5 mb-1">As at {{ Carbon\Carbon::parse($asOfDate)->format('d F Y') }}</h3>
                <p class="text-muted">In accordance with International Public Sector Accounting Standards (IPSAS)</p>
                <p class="text-muted">Amounts in {{ $currency }}</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- ASSETS -->
                    <h4 class="text-primary border-bottom pb-2">ASSETS</h4>
                    
                    <!-- Current Assets -->
                    <h5 class="mt-4 mb-3">Current Assets</h5>
                    <table class="table table-sm">
                        @foreach($currentAssets as $asset)
                        <tr>
                            <td>{{ $asset->account_name }}</td>
                            <td class="text-end">{{ number_format($asset->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td>Total Current Assets</td>
                            <td class="text-end">{{ number_format($currentAssets->sum('balance'), 2) }}</td>
                        </tr>
                    </table>

                    <!-- Non-Current Assets -->
                    <h5 class="mt-4 mb-3">Non-Current Assets</h5>
                    <table class="table table-sm">
                        @foreach($nonCurrentAssets as $asset)
                        <tr>
                            <td>{{ $asset->account_name }}</td>
                            <td class="text-end">{{ number_format($asset->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td>Total Non-Current Assets</td>
                            <td class="text-end">{{ number_format($nonCurrentAssets->sum('balance'), 2) }}</td>
                        </tr>
                    </table>

                    <div class="bg-primary text-white p-2 rounded mt-3">
                        <div class="d-flex justify-content-between">
                            <strong>TOTAL ASSETS</strong>
                            <strong>{{ number_format($totalAssets, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- LIABILITIES -->
                    <h4 class="text-danger border-bottom pb-2">LIABILITIES</h4>
                    
                    <!-- Current Liabilities -->
                    <h5 class="mt-4 mb-3">Current Liabilities</h5>
                    <table class="table table-sm">
                        @foreach($currentLiabilities as $liability)
                        <tr>
                            <td>{{ $liability->account_name }}</td>
                            <td class="text-end">{{ number_format($liability->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td>Total Current Liabilities</td>
                            <td class="text-end">{{ number_format($currentLiabilities->sum('balance'), 2) }}</td>
                        </tr>
                    </table>

                    <!-- Non-Current Liabilities -->
                    <h5 class="mt-4 mb-3">Non-Current Liabilities</h5>
                    <table class="table table-sm">
                        @foreach($nonCurrentLiabilities as $liability)
                        <tr>
                            <td>{{ $liability->account_name }}</td>
                            <td class="text-end">{{ number_format($liability->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td>Total Non-Current Liabilities</td>
                            <td class="text-end">{{ number_format($nonCurrentLiabilities->sum('balance'), 2) }}</td>
                        </tr>
                    </table>

                    <div class="bg-danger text-white p-2 rounded mt-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>TOTAL LIABILITIES</strong>
                            <strong>{{ number_format($totalLiabilities, 2) }}</strong>
                        </div>
                    </div>

                    <!-- NET ASSETS/EQUITY -->
                    <h4 class="text-success border-bottom pb-2">NET ASSETS/EQUITY</h4>
                    
                    <table class="table table-sm mt-3">
                        @foreach($netAssets as $equity)
                        <tr>
                            <td>{{ $equity->account_name }}</td>
                            <td class="text-end">{{ number_format($equity->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td>Total Net Assets/Equity</td>
                            <td class="text-end">{{ number_format($totalNetAssets, 2) }}</td>
                        </tr>
                    </table>

                    <div class="bg-success text-white p-2 rounded mt-3">
                        <div class="d-flex justify-content-between">
                            <strong>TOTAL LIABILITIES AND NET ASSETS</strong>
                            <strong>{{ number_format($totalLiabilities + $totalNetAssets, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IPSAS Compliance Note -->
            <div class="mt-5 p-3 bg-light rounded">
                <h6 class="text-primary">IPSAS Compliance Notes:</h6>
                <ul class="mb-0 small">
                    <li>This statement is prepared in accordance with IPSAS 1 - Presentation of Financial Statements</li>
                    <li>Assets and liabilities are classified as current and non-current as per IPSAS requirements</li>
                    <li>All amounts are presented in {{ $currency }} as at {{ Carbon\Carbon::parse($asOfDate)->format('d F Y') }}</li>
                    <li>Comparative figures and notes to financial statements are available in separate reports</li>
                </ul>
            </div>

            <!-- Verification Section -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 50px;"></div>
                        <small>Prepared by</small><br>
                        <small>{{ auth()->user()->name }}</small><br>
                        <small>{{ now()->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 50px;"></div>
                        <small>Reviewed by</small><br>
                        <small>Finance Manager</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 50px;"></div>
                        <small>Approved by</small><br>
                        <small>Chief Executive Officer</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .form-control, .form-select, .d-sm-flex {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
