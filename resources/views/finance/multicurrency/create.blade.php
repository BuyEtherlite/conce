@extends('layouts.admin')

@section('page-title', 'Add New Currency')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💱 Add New Currency</h4>
        <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Currencies
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="m-0">Currency Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('finance.multicurrency.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currency_code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('currency_code') is-invalid @enderror" 
                                   id="currency_code" name="currency_code" value="{{ old('currency_code') }}" 
                                   maxlength="3" placeholder="e.g., USD, EUR, GBP" required>
                            <div class="form-text">3-letter currency code (ISO 4217)</div>
                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currency_name" class="form-label">Currency Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('currency_name') is-invalid @enderror" 
                                   id="currency_name" name="currency_name" value="{{ old('currency_name') }}" 
                                   placeholder="e.g., US Dollar, Euro, British Pound" required>
                            @error('currency_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currency_symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                   id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol') }}" 
                                   maxlength="10" placeholder="e.g., $, €, £" required>
                            @error('currency_symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="decimal_places" class="form-label">Decimal Places <span class="text-danger">*</span></label>
                            <select class="form-select @error('decimal_places') is-invalid @enderror" 
                                    id="decimal_places" name="decimal_places" required>
                                <option value="">Select decimal places</option>
                                <option value="0" {{ old('decimal_places') == '0' ? 'selected' : '' }}>0 (e.g., JPY)</option>
                                <option value="2" {{ old('decimal_places') == '2' ? 'selected' : '' }}>2 (e.g., USD, EUR)</option>
                                <option value="3" {{ old('decimal_places') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('decimal_places') == '4' ? 'selected' : '' }}>4</option>
                            </select>
                            @error('decimal_places')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exchange_rate" class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('exchange_rate') is-invalid @enderror" id="exchange_rate" name="exchange_rate" 
                           step="0.000001" min="0" value="{{ old('exchange_rate', '1.000000') }}" required>
                    <div class="form-text">Exchange rate relative to the base currency (1.0 for base currency)</div>
                    @error('exchange_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input @error('is_base_currency') is-invalid @enderror" 
                               type="checkbox" id="is_base_currency" name="is_base_currency" value="1" 
                               {{ old('is_base_currency') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_base_currency">
                            Set as Base Currency
                        </label>
                        <div class="form-text">
                            The base currency is used as the reference for exchange rates. Only one base currency is allowed.
                        </div>
                        @error('is_base_currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Currency
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currencyCodeInput = document.getElementById('currency_code');
    const exchangeRateInput = document.getElementById('exchange_rate');
    const isBaseCurrencyCheckbox = document.getElementById('is_base_currency');

    currencyCodeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    isBaseCurrencyCheckbox.addEventListener('change', function() {
        if (this.checked) {
            exchangeRateInput.value = '1.000000';
            exchangeRateInput.disabled = true;
        } else {
            exchangeRateInput.disabled = false;
            // If the old value exists and is not '1.000000', restore it
            if (exchangeRateInput.value === '1.000000' && {{ old('exchange_rate') ? 'true' : 'false' }}) {
                 exchangeRateInput.value = "{{ old('exchange_rate') }}";
            } else if (exchangeRateInput.value === '') {
                 exchangeRateInput.value = '1.000000';
            }
        }
    });

    // Initialize the exchange rate input based on the checkbox state on page load
    if (isBaseCurrencyCheckbox.checked) {
        exchangeRateInput.value = '1.000000';
        exchangeRateInput.disabled = true;
    } else {
        exchangeRateInput.disabled = false;
        // If the old value exists and is not '1.000000', restore it
        if (exchangeRateInput.value === '1.000000' && {{ old('exchange_rate') ? 'true' : 'false' }}) {
             exchangeRateInput.value = "{{ old('exchange_rate') }}";
        } else if (exchangeRateInput.value === '') {
             exchangeRateInput.value = '1.000000';
        }
    }
});
</script>
@endsection