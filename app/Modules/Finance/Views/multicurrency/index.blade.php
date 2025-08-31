@extends('layouts.admin')

@section('page-title', 'Multicurrency Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💱 Multicurrency Management</h4>
        <div>
            <a href="{{ route('finance.multicurrency.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Currency
            </a>
            <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-line me-1"></i>Exchange Rates
            </a>
            <a href="{{ route('finance.multicurrency.conversion') }}" class="btn btn-outline-info">
                <i class="fas fa-exchange-alt me-1"></i>Currency Converter
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Active Currencies</h6>
                </div>
                <div class="card-body">
                    @if($currencies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Currency Code</th>
                                        <th>Currency Name</th>
                                        <th>Symbol</th>
                                        <th>Decimal Places</th>
                                        <th>Current Rate</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currencies as $currency)
                                        <tr>
                                            <td>
                                                <strong>{{ $currency->currency_code }}</strong>
                                                @if($currency->is_base_currency)
                                                    <span class="badge bg-primary ms-1">Base</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency->currency_name }}</td>
                                            <td>{{ $currency->currency_symbol }}</td>
                                            <td>{{ $currency->decimal_places }}</td>
                                            <td>
                                                @if($currency->is_base_currency)
                                                    1.0000
                                                @else
                                                    {{ $currency->latestRate ? number_format($currency->latestRate->exchange_rate, 4) : 'Not Set' }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $currency->is_active ? 'success' : 'secondary' }}">
                                                    {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="#" class="btn btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-coins fa-3x text-muted mb-3"></i>
                            <h5>No Currencies Found</h5>
                            <p class="text-muted">Start by adding your first currency.</p>
                            <a href="{{ route('finance.multicurrency.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Currency
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-line me-2"></i>Manage Exchange Rates
                        </a>
                        <a href="{{ route('finance.multicurrency.conversion') }}" class="btn btn-outline-success">
                            <i class="fas fa-exchange-alt me-2"></i>Currency Converter
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-file-alt me-2"></i>Currency Reports
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="m-0">Exchange Rate Summary</h6>
                </div>
                <div class="card-body">
                    @if($currencies->where('is_base_currency', false)->count() > 0)
                        @foreach($currencies->where('is_base_currency', false)->take(5) as $currency)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $currency->currency_code }}</span>
                                <span class="fw-bold">
                                    {{ $currency->latestRate ? number_format($currency->latestRate->exchange_rate, 4) : 'N/A' }}
                                </span>
                            </div>
                        @endforeach
                        <hr>
                        <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-sm btn-outline-primary w-100">
                            View All Rates
                        </a>
                    @else
                        <p class="text-muted text-center">No exchange rates available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Multi-Currency Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Multi-Currency Management</h4>
                    <div class="card-tools">
                        <a href="{{ route('finance.multicurrency.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Currency
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Exchange Rate</th>
                                    <th>Base Currency</th>
                                    <th>Status</th>
                                    <th>Latest Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                <tr>
                                    <td>{{ $currency->currency_code }}</td>
                                    <td>{{ $currency->currency_name }}</td>
                                    <td>{{ $currency->currency_symbol }}</td>
                                    <td>{{ number_format($currency->exchange_rate, 6) }}</td>
                                    <td>
                                        @if($currency->is_base_currency)
                                            <span class="badge badge-primary">Base</span>
                                        @else
                                            <span class="badge badge-secondary">Secondary</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($currency->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($currency->latestRate)
                                            {{ number_format($currency->latestRate->exchange_rate, 6) }}
                                            <small class="text-muted">({{ $currency->latestRate->effective_date->format('M d, Y') }})</small>
                                        @else
                                            <span class="text-muted">No rates</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('finance.multicurrency.show', $currency) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('finance.multicurrency.edit', $currency) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No currencies found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                            <h5>Exchange Rates</h5>
                            <p class="text-muted">Manage currency exchange rates</p>
                            <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-primary">
                                Manage Rates
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                            <h5>Currency Converter</h5>
                            <p class="text-muted">Convert between currencies</p>
                            <a href="{{ route('finance.multicurrency.conversion') }}" class="btn btn-success">
                                Convert Currency
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                            <h5>Rate History</h5>
                            <p class="text-muted">View exchange rate trends</p>
                            <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-info">
                                View History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
