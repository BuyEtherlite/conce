@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📄 Create Invoice</h1>
                <a href="{{ route('finance.receivables.invoices') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>

            <form method="POST" action="{{ route('finance.receivables.invoices.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Invoice Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Customer *</label>
                                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                                <option value="">Select Customer</option>
                                                @foreach($customers ?? [] as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Invoice Number</label>
                                            <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" 
                                                   value="{{ old('invoice_number', $invoice_number ?? '') }}" readonly>
                                            @error('invoice_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Invoice Date *</label>
                                            <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                                   value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                            @error('invoice_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Due Date *</label>
                                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                                   value="{{ old('due_date') }}" required>
                                            @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Invoice Items -->
                                <div class="mb-3">
                                    <label class="form-label">Invoice Items</label>
                                    <div id="invoice-items">
                                        <div class="row invoice-item mb-2">
                                            <div class="col-md-4">
                                                <input type="text" name="items[0][description]" class="form-control" placeholder="Item description" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="items[0][quantity]" class="form-control item-quantity" placeholder="Qty" value="1" min="1" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="items[0][unit_price]" class="form-control item-price" placeholder="Unit Price" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="items[0][total]" class="form-control item-total" placeholder="Total" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">Add Item</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Invoice Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-8">Subtotal:</div>
                                    <div class="col-4 text-end">R <span id="subtotal">0.00</span></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-8">Tax (15%):</div>
                                    <div class="col-4 text-end">R <span id="tax">0.00</span></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-8"><strong>Total:</strong></div>
                                    <div class="col-4 text-end"><strong>R <span id="total">0.00</span></strong></div>
                                </div>

                                <input type="hidden" name="subtotal" id="subtotal-input" value="0">
                                <input type="hidden" name="tax_amount" id="tax-input" value="0">
                                <input type="hidden" name="total_amount" id="total-input" value="0">
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="save_draft" class="btn btn-outline-primary">Save as Draft</button>
                                    <button type="submit" name="action" value="send" class="btn btn-primary">Create & Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.createElement('div');
        newItem.className = 'row invoice-item mb-2';
        newItem.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Item description" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" placeholder="Qty" value="1" min="1" step="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control item-price" placeholder="Unit Price" step="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][total]" class="form-control item-total" placeholder="Total" readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            </div>
        `;
        itemsContainer.appendChild(newItem);
        itemIndex++;
        attachItemEventListeners(newItem);
    });

    // Remove item functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.invoice-item').remove();
            calculateTotals();
        }
    });

    // Attach event listeners to item inputs
    function attachItemEventListeners(container) {
        const quantityInput = container.querySelector('.item-quantity');
        const priceInput = container.querySelector('.item-price');
        
        [quantityInput, priceInput].forEach(input => {
            input.addEventListener('input', function() {
                calculateItemTotal(container);
                calculateTotals();
            });
        });
    }

    // Calculate item total
    function calculateItemTotal(container) {
        const quantity = parseFloat(container.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(container.querySelector('.item-price').value) || 0;
        const total = quantity * price;
        container.querySelector('.item-total').value = total.toFixed(2);
    }

    // Calculate overall totals
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });

        const tax = subtotal * 0.15;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('tax').textContent = tax.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);

        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('tax-input').value = tax.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
    }

    // Attach event listeners to initial item
    const initialItem = document.querySelector('.invoice-item');
    if (initialItem) {
        attachItemEventListeners(initialItem);
    }
});
</script>
@endsection
