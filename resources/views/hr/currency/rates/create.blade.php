@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Add New Currency Rate</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('hr.currency.rates.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="currency_id">Currency</label>
                            <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->currency_code }} - {{ $currency->currency_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exchange_rate">Exchange Rate</label>
                            <input type="number" step="0.000001" name="exchange_rate" id="exchange_rate"
                                   class="form-control @error('exchange_rate') is-invalid @enderror"
                                   value="{{ old('exchange_rate') }}" required>
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rate_type">Rate Type</label>
                            <select name="rate_type" id="rate_type" class="form-control @error('rate_type') is-invalid @enderror">
                                <option value="buy" {{ old('rate_type') == 'buy' ? 'selected' : '' }}>Buy</option>
                                <option value="sell" {{ old('rate_type') == 'sell' ? 'selected' : '' }}>Sell</option>
                                <option value="mid" {{ old('rate_type') == 'mid' ? 'selected' : '' }}>Mid</option>
                            </select>
                            @error('rate_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="effective_date">Effective Date</label>
                            <input type="date" name="effective_date" id="effective_date"
                                   class="form-control @error('effective_date') is-invalid @enderror"
                                   value="{{ old('effective_date', date('Y-m-d')) }}" required>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                       {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Currency Rate</button>
                            <a href="{{ route('hr.currency.rates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection