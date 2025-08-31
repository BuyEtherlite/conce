@extends('layouts.admin')

@section('page-title', 'Currency Converter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💱 Currency Converter</h4>
        <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Currencies
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Currency Conversion</h6>
                </div>
                <div class="card-body">
                    <form id="conversionForm">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="from_currency" class="form-label">From Currency</label>
                                    <select class="form-select" id="from_currency" name="from_currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->currency_code }} - {{ $currency->currency_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="0.01" min="0" class="form-control" 
                                           id="amount" name="amount" placeholder="Enter amount" value="1">
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <button type="button" class="btn btn-outline-primary" id="swapCurrencies">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="to_currency" class="form-label">To Currency</label>
                                    <select class="form-select" id="to_currency" name="to_currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->currency_code }} - {{ $currency->currency_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="converted_amount" class="form-label">Converted Amount</label>
                                    <input type="text" class="form-control" id="converted_amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="convertButton">
                                <i class="fas fa-calculator me-1"></i>Convert
                            </button>
                        </div>
                    </form>

                    <div id="conversionResult" class="mt-4" style="display: none;">
                        <div class="alert alert-info">
                            <h6>Conversion Result:</h6>
                            <p class="mb-0" id="resultText"></p>
                            <small class="text-muted" id="rateInfo"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Current Exchange Rates</h6>
                </div>
                <div class="card-body">
                    @if($currencies->where('is_base_currency', false)->count() > 0)
                        @foreach($currencies->where('is_base_currency', false) as $currency)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $currency->currency_code }}</span>
                                <span class="fw-bold">
                                    {{ $currency->latestRate ? number_format($currency->latestRate->exchange_rate, 4) : 'N/A' }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No exchange rates available</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="m-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.multicurrency.rates') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-line me-2"></i>Manage Rates
                        </a>
                        <a href="{{ route('finance.multicurrency.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Add Currency
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const convertButton = document.getElementById('convertButton');
    const swapButton = document.getElementById('swapCurrencies');
    const fromCurrency = document.getElementById('from_currency');
    const toCurrency = document.getElementById('to_currency');
    const amount = document.getElementById('amount');
    const convertedAmount = document.getElementById('converted_amount');
    const conversionResult = document.getElementById('conversionResult');
    const resultText = document.getElementById('resultText');
    const rateInfo = document.getElementById('rateInfo');

    // Mock conversion rates (replace with actual API call)
    const exchangeRates = {
        @foreach($currencies as $currency)
        {{ $currency->id }}: {
            code: '{{ $currency->currency_code }}',
            name: '{{ $currency->currency_name }}',
            rate: {{ $currency->latestRate ? $currency->latestRate->exchange_rate : 1 }},
            is_base: {{ $currency->is_base_currency ? 'true' : 'false' }}
        },
        @endforeach
    };

    convertButton.addEventListener('click', function() {
        const fromId = fromCurrency.value;
        const toId = toCurrency.value;
        const amountValue = parseFloat(amount.value) || 0;

        if (!fromId || !toId || amountValue <= 0) {
            alert('Please select currencies and enter a valid amount');
            return;
        }

        const fromRate = exchangeRates[fromId];
        const toRate = exchangeRates[toId];

        // Convert to base currency first, then to target currency
        let baseAmount = fromRate.is_base ? amountValue : amountValue / fromRate.rate;
        let convertedValue = toRate.is_base ? baseAmount : baseAmount * toRate.rate;

        convertedAmount.value = convertedValue.toFixed(4);
        
        resultText.textContent = `${amountValue} ${fromRate.code} = ${convertedValue.toFixed(4)} ${toRate.code}`;
        rateInfo.textContent = `Exchange rate: 1 ${fromRate.code} = ${(convertedValue / amountValue).toFixed(4)} ${toRate.code}`;
        
        conversionResult.style.display = 'block';
    });

    swapButton.addEventListener('click', function() {
        const fromValue = fromCurrency.value;
        const toValue = toCurrency.value;
        
        fromCurrency.value = toValue;
        toCurrency.value = fromValue;
        
        // Clear previous results
        convertedAmount.value = '';
        conversionResult.style.display = 'none';
    });

    // Auto-convert when amount changes
    amount.addEventListener('input', function() {
        if (fromCurrency.value && toCurrency.value && this.value) {
            convertButton.click();
        }
    });
});
</script>
@endsection
