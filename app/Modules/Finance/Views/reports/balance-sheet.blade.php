@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">📊 Balance Sheet</h3>
                    <div>
                        <form action="{{ route('finance.reports.balance-sheet') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="as_of_date" class="mr-2">As of:</label>
                                <input type="date" class="form-control" id="as_of_date" name="as_of_date" value="{{ $asOfDate }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4>{{ config('app.name') }}</h4>
                        <h5>Balance Sheet</h5>
                        <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('F j, Y') }}</p>
                    </div>

                    <div class="row">
                        <!-- Assets -->
                        <div class="col-md-6">
                            <h5 class="text-primary"><strong>ASSETS</strong></h5>
                            <table class="table table-sm">
                                @php $totalAssets = 0; @endphp
                                @foreach($data['assets'] as $asset)
                                    @php $totalAssets += $asset['balance']; @endphp
                                    <tr>
                                        <td>{{ $asset['account']->account_name }}</td>
                                        <td class="text-right">${{ number_format(abs($asset['balance']), 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-primary">
                                    <td><strong>Total Assets</strong></td>
                                    <td class="text-right"><strong>${{ number_format(abs($totalAssets), 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Liabilities & Equity -->
                        <div class="col-md-6">
                            <h5 class="text-danger"><strong>LIABILITIES</strong></h5>
                            <table class="table table-sm">
                                @php $totalLiabilities = 0; @endphp
                                @foreach($data['liabilities'] as $liability)
                                    @php $totalLiabilities += $liability['balance']; @endphp
                                    <tr>
                                        <td>{{ $liability['account']->account_name }}</td>
                                        <td class="text-right">${{ number_format(abs($liability['balance']), 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-danger">
                                    <td><strong>Total Liabilities</strong></td>
                                    <td class="text-right"><strong>${{ number_format(abs($totalLiabilities), 2) }}</strong></td>
                                </tr>
                            </table>

                            <h5 class="text-success mt-3"><strong>EQUITY</strong></h5>
                            <table class="table table-sm">
                                @php $totalEquity = 0; @endphp
                                @foreach($data['equity'] as $equity)
                                    @php $totalEquity += $equity['balance']; @endphp
                                    <tr>
                                        <td>{{ $equity['account']->account_name }}</td>
                                        <td class="text-right">${{ number_format(abs($equity['balance']), 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-success">
                                    <td><strong>Total Equity</strong></td>
                                    <td class="text-right"><strong>${{ number_format(abs($totalEquity), 2) }}</strong></td>
                                </tr>
                            </table>

                            <table class="table table-sm table-dark">
                                <tr>
                                    <td><strong>Total Liabilities & Equity</strong></td>
                                    <td class="text-right"><strong>${{ number_format(abs($totalLiabilities + $totalEquity), 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Balance Check -->
                    <div class="row mt-3">
                        <div class="col-12">
                            @php $balanced = abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01; @endphp
                            <div class="alert {{ $balanced ? 'alert-success' : 'alert-danger' }}">
                                <strong>Balance Check:</strong> 
                                {{ $balanced ? 'The balance sheet is balanced!' : 'The balance sheet is NOT balanced!' }}
                                @if (!$balanced)
                                    <br>Difference: ${{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
