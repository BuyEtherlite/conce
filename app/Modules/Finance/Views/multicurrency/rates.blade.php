@extends('layouts.admin')

@section('page-title', 'Exchange Rates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💱 Exchange Rates Management</h4>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRateModal">
                <i class="fas fa-plus me-1"></i>Add Rate
            </button>
            <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Currencies
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="m-0">Current Exchange Rates</h6>
        </div>
        <div class="card-body">
            @if($rates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Exchange Rate</th>
                                <th>Rate Type</th>
                                <th>Effective Date</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rates as $rate)
                                <tr>
                                    <td>
                                        <strong>{{ $rate->currency->currency_code }}</strong>
                                        <small class="text-muted d-block">{{ $rate->currency->currency_name }}</small>
                                    </td>
                                    <td>{{ number_format($rate->exchange_rate, 4) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rate->rate_type === 'buying' ? 'success' : ($rate->rate_type === 'selling' ? 'danger' : 'primary') }}">
                                            {{ ucfirst($rate->rate_type) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($rate->effective_date)->format('M d, Y') }}</td>
                                    <td>{{ $rate->creator->name ?? 'System' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary edit-rate" 
                                                    data-id="{{ $rate->id }}" 
                                                    data-currency="{{ $rate->currency_id }}"
                                                    data-rate="{{ $rate->exchange_rate }}"
                                                    data-type="{{ $rate->rate_type }}"
                                                    data-date="{{ $rate->effective_date }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $rates->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5>No Exchange Rates Found</h5>
                    <p class="text-muted">Start by adding exchange rates for your currencies.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRateModal">
                        <i class="fas fa-plus me-1"></i>Add Exchange Rate
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1" aria-labelledby="addRateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRateModalLabel">Add Exchange Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.multicurrency.rates.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                        <select class="form-select" id="currency_id" name="currency_id" required>
                            <option value="">Select Currency</option>
                            @foreach($currencies as $currency)
                                @if(!$currency->is_base_currency)
                                    <option value="{{ $currency->id }}">{{ $currency->currency_code }} - {{ $currency->currency_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" min="0" class="form-control" 
                               id="exchange_rate" name="exchange_rate" required>
                        <div class="form-text">Rate relative to base currency</div>
                    </div>

                    <div class="mb-3">
                        <label for="rate_type" class="form-label">Rate Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="rate_type" name="rate_type" required>
                            <option value="">Select Type</option>
                            <option value="buying">Buying</option>
                            <option value="selling">Selling</option>
                            <option value="mid">Mid Rate</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Exchange Rates')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Exchange Rates Management</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRateModal">
                            <i class="fas fa-plus"></i> Add Rate
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Exchange Rate</th>
                                    <th>Rate Type</th>
                                    <th>Effective Date</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $rate)
                                <tr>
                                    <td>
                                        <strong>{{ $rate->currency->currency_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $rate->currency->currency_name }}</small>
                                    </td>
                                    <td>{{ number_format($rate->exchange_rate, 6) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rate->rate_type == 'buying' ? 'success' : ($rate->rate_type == 'selling' ? 'danger' : 'info') }}">
                                            {{ ucfirst($rate->rate_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $rate->effective_date->format('M d, Y') }}</td>
                                    <td>{{ $rate->creator->name ?? 'System' }}</td>
                                    <td>
                                        @if($rate->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" onclick="editRate({{ $rate->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($rate->is_active)
                                                <button class="btn btn-secondary" onclick="deactivateRate({{ $rate->id }})">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No exchange rates found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $rates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('finance.multicurrency.rates.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Exchange Rate</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="currency_id">Currency</label>
                        <select name="currency_id" id="currency_id" class="form-control" required>
                            <option value="">Select Currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency_code }} - {{ $currency->currency_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="exchange_rate">Exchange Rate</label>
                        <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" step="0.000001" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="rate_type">Rate Type</label>
                        <select name="rate_type" id="rate_type" class="form-control" required>
                            <option value="mid">Mid Rate</option>
                            <option value="buying">Buying Rate</option>
                            <option value="selling">Selling Rate</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="effective_date">Effective Date</label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
