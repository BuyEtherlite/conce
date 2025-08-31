@extends('layouts.admin')

@section('title', 'Currency Converter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Currency Converter</h3>
                    <div class="card-tools">
                        <a href="{{ route('finance.multicurrency.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Currencies
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="converterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_currency">From Currency</label>
                                    <select class="form-control" id="from_currency" name="from_currency" required>
                                        <option value="">Select Currency</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->currency_code }} - {{ $currency->currency_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_currency">To Currency</label>
                                    <select class="form-control" id="to_currency" name="to_currency" required>
                                        <option value="">Select Currency</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->currency_code }} - {{ $currency->currency_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-exchange-alt"></i> Convert
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="conversionResult" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <h5>Conversion Result</h5>
                            <p id="resultText"></p>
                            <small class="text-muted">Exchange Rate: <span id="exchangeRate"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('converterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("finance.multicurrency.convert") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('resultText').textContent = 
                `${formData.get('amount')} ${data.from_currency} = ${data.converted_amount} ${data.to_currency}`;
            document.getElementById('exchangeRate').textContent = data.exchange_rate;
            document.getElementById('conversionResult').style.display = 'block';
        } else {
            alert('Conversion failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during conversion.');
    });
});
</script>
@endsection
