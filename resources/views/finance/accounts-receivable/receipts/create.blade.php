@extends('layouts.admin')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>💰 Record Payment</h1>
                <a href="{{ route('finance.accounts-receivable.payments') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_id">Customer *</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="receipt_date">Receipt Date *</label>
                                    <input type="date" class="form-control" id="receipt_date" name="receipt_date" required value="{{ old('receipt_date', date('Y-m-d')) }}">
                                    @error('receipt_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="amount_received">Amount Received *</label>
                                    <input type="number" class="form-control" id="amount_received" name="amount_received" step="0.01" min="0.01" required value="{{ old('amount_received') }}">
                                    @error('amount_received')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_method">Payment Method *</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Select Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reference_number">Reference Number</label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number" value="{{ old('reference_number') }}">
                            @error('reference_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="invoice-allocation" style="display: none;">
                            <h5>Invoice Allocation</h5>
                            <div id="invoices-list"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Payment
                            </button>
                            <a href="{{ route('finance.accounts-receivable.payments') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('customer_id').addEventListener('change', function() {
    const customerId = this.value;
    if (customerId) {
        fetch(`/finance/accounts-receivable/customers/${customerId}/invoices`)
            .then(response => response.json())
            .then(data => {
                const invoicesList = document.getElementById('invoices-list');
                invoicesList.innerHTML = '';

                if (data.length > 0) {
                    document.getElementById('invoice-allocation').style.display = 'block';
                    data.forEach(invoice => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="invoices[]" value="${invoice.id}" id="invoice_${invoice.id}">
                            <label class="form-check-label" for="invoice_${invoice.id}">
                                ${invoice.invoice_number} - $${parseFloat(invoice.balance_due).toFixed(2)} (Due: ${invoice.due_date})
                            </label>
                        `;
                        invoicesList.appendChild(div);
                    });
                } else {
                    document.getElementById('invoice-allocation').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching invoices:', error);
                document.getElementById('invoice-allocation').style.display = 'none';
            });
    } else {
        document.getElementById('invoice-allocation').style.display = 'none';
    }
});
</script>
@endsection