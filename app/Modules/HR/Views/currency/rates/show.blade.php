@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Currency Rate Details</h4>
                    <div>
                        <a href="{{ route('hr.currency.rates.edit', $rate) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('hr.currency.rates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Currency</th>
                                    <td>{{ $rate->currency->code ?? 'N/A' }} - {{ $rate->currency->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Exchange Rate</th>
                                    <td>{{ number_format($rate->exchange_rate, 6) }}</td>
                                </tr>
                                <tr>
                                    <th>Rate Type</th>
                                    <td>
                                        <span class="badge badge-{{ $rate->rate_type === 'buy' ? 'success' : ($rate->rate_type === 'sell' ? 'danger' : 'info') }}">
                                            {{ ucfirst($rate->rate_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Effective Date</th>
                                    <td>{{ $rate->effective_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $rate->is_active ? 'success' : 'secondary' }}">
                                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $rate->creator->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $rate->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $rate->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Rate History</h6>
                                </div>
                                <div class="card-body">
                                    @if($rate->currency && $rate->currency->currencyRates->count() > 1)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Rate</th>
                                                        <th>Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($rate->currency->currencyRates->sortByDesc('effective_date')->take(5) as $historicalRate)
                                                    <tr class="{{ $historicalRate->id === $rate->id ? 'table-primary' : '' }}">
                                                        <td>{{ $historicalRate->effective_date->format('M d') }}</td>
                                                        <td>{{ number_format($historicalRate->exchange_rate, 4) }}</td>
                                                        <td>{{ ucfirst($historicalRate->rate_type) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No historical data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection