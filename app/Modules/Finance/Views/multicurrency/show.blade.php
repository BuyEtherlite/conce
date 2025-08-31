@extends('layouts.admin')

@section('title', 'Currency Details - ' . $currency->currency_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-coins mr-2"></i>
                        {{ $currency->currency_name }} ({{ $currency->currency_code }})
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.multicurrency.edit', $currency) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Currency Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Currency Code:</strong></td>
                                    <td>{{ $currency->currency_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Currency Name:</strong></td>
                                    <td>{{ $currency->currency_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Symbol:</strong></td>
                                    <td>{{ $currency->currency_symbol }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Decimal Places:</strong></td>
                                    <td>{{ $currency->decimal_places }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Base Currency:</strong></td>
                                    <td>
                                        @if($currency->is_base_currency)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($currency->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Current Rate:</strong></td>
                                    <td>{{ number_format($currentRate, 6) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistics</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Transactions:</strong></td>
                                    <td>{{ number_format($transactionCount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Value:</strong></td>
                                    <td>{{ $currency->currency_symbol }} {{ number_format($totalValue, $currency->decimal_places) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Exchange Rates:</strong></td>
                                    <td>{{ $currency->rates->count() }} rates recorded</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($currency->rates->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Recent Exchange Rates</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Rate</th>
                                            <th>Type</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($currency->rates as $rate)
                                        <tr>
                                            <td>{{ $rate->effective_date->format('Y-m-d') }}</td>
                                            <td>{{ number_format($rate->exchange_rate, 6) }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($rate->rate_type) }}</span>
                                            </td>
                                            <td>{{ $rate->creator->name ?? 'System' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
