@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Currency Rate</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('hr.currency.rates.update', $rate) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="currency_id">Currency</label>
                            <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $currency->id == $currencyRate->currency_id ? 'selected' : '' }}>
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
                                   value="{{ old('exchange_rate', $rate->exchange_rate) }}" required>
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rate_type">Rate Type</label>
                            <select name="rate_type" id="rate_type" class="form-control @error('rate_type') is-invalid @enderror">
                                <option value="buy" {{ old('rate_type', $rate->rate_type) == 'buy' ? 'selected' : '' }}>Buy</option>
                                <option value="sell" {{ old('rate_type', $rate->rate_type) == 'sell' ? 'selected' : '' }}>Sell</option>
                                <option value="mid" {{ old('rate_type', $rate->rate_type) == 'mid' ? 'selected' : '' }}>Mid</option>
                            </select>
                            @error('rate_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="effective_date">Effective Date</label>
                            <input type="date" name="effective_date" id="effective_date" 
                                   class="form-control @error('effective_date') is-invalid @enderror" 
                                   value="{{ old('effective_date', $rate->effective_date->format('Y-m-d')) }}" required>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                       {{ old('is_active', $rate->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Currency Rate</button>
                            <a href="{{ route('hr.currency.rates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection