@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Currency Exchange Rates</h4>
                    <a href="{{ route('hr.currency.rates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Rate
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Exchange Rate</th>
                                    <th>Rate Type</th>
                                    <th>Effective Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $rate)
                                <tr>
                                    <td>{{ $rate->currency->currency_code ?? 'N/A' }} - {{ $rate->currency->currency_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($rate->exchange_rate, 6) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rate->rate_type === 'buy' ? 'success' : ($rate->rate_type === 'sell' ? 'danger' : 'info') }}">
                                            {{ ucfirst($rate->rate_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $rate->effective_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rate->is_active ? 'success' : 'secondary' }}">
                                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $rate->creator->name ?? 'System' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('hr.currency.rates.show', $rate) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hr.currency.rates.edit', $rate) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hr.currency.rates.destroy', $rate) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No currency rates found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $rates->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection