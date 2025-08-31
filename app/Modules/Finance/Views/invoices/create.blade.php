@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create Invoice</h4>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('finance.invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-select" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" 
                                           value="{{ old('invoice_number', $invoiceNumber) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">Invoice Date</label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control" 
                                           value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" 
                                           value="{{ old('due_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <h5>Invoice Items</h5>
                            <div id="invoice-items">
                                <div class="invoice-item row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" name="items[0][description]" class="form-control" 
                                               placeholder="Item description" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[0][quantity]" class="form-control quantity" 
                                               placeholder="Qty" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[0][rate]" class="form-control rate" 
                                               placeholder="Rate" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control amount" readonly placeholder="Amount">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-item" class="btn btn-sm btn-secondary">Add Item</button>
                        </div>

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td class="text-end" id="subtotal">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tax (15%):</td>
                                        <td class="text-end" id="tax">$0.00</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total:</td>
                                        <td class="text-end" id="total">$0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('invoice-items');
        const newItem = document.createElement('div');
        newItem.className = 'invoice-item row mb-2';
        newItem.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="items[${itemIndex}][description]" class="form-control" 
                       placeholder="Item description" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" 
                       placeholder="Qty" step="0.01" min="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][rate]" class="form-control rate" 
                       placeholder="Rate" step="0.01" min="0" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control amount" readonly placeholder="Amount">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        attachItemEvents(newItem);
    });

    // Remove item functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const items = document.querySelectorAll('.invoice-item');
            if (items.length > 1) {
                e.target.closest('.invoice-item').remove();
                calculateTotals();
            }
        }
    });

    // Calculate amounts
    function attachItemEvents(item) {
        const quantity = item.querySelector('.quantity');
        const rate = item.querySelector('.rate');
        const amount = item.querySelector('.amount');

        function updateAmount() {
            const qty = parseFloat(quantity.value) || 0;
            const rt = parseFloat(rate.value) || 0;
            const amt = qty * rt;
            amount.value = '$' + amt.toFixed(2);
            calculateTotals();
        }

        quantity.addEventListener('input', updateAmount);
        rate.addEventListener('input', updateAmount);
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.invoice-item').forEach(item => {
            const qty = parseFloat(item.querySelector('.quantity').value) || 0;
            const rate = parseFloat(item.querySelector('.rate').value) || 0;
            subtotal += qty * rate;
        });

        const tax = subtotal * 0.15;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    // Attach events to initial item
    attachItemEvents(document.querySelector('.invoice-item'));
});
</script>
@endsection
