@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📄 Create Invoice</h1>
                <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="nav-link active">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.customers.index') }}" class="nav-link">Customers</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finance.accounts-receivable.payments') }}" class="nav-link">Payments</a>
                </li>
            </ul>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('finance.accounts-receivable.invoices.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_id">Customer *</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->first_name }} {{ $customer->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="invoice_date">Invoice Date *</label>
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" required value="{{ old('invoice_date', date('Y-m-d')) }}">
                                    @error('invoice_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="due_date">Due Date *</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="program_code">Program Code</label>
                                    <input type="text" class="form-control" id="program_code" name="program_code" value="{{ old('program_code') }}">
                                    @error('program_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="project_code">Project Code</label>
                            <input type="text" class="form-control" id="project_code" name="project_code" value="{{ old('project_code') }}">
                            @error('project_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5>Invoice Items</h5>
                        <div id="invoice-items">
                            <div class="invoice-item mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Description *</label>
                                        <input type="text" class="form-control" name="items[0][description]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Quantity *</label>
                                        <input type="number" class="form-control item-quantity" name="items[0][quantity]" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Unit Price *</label>
                                        <input type="number" class="form-control item-price" name="items[0][unit_price]" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Revenue Account *</label>
                                        <select class="form-control" name="items[0][account_code]" required>
                                            <option value="">Select Account</option>
                                            @foreach($revenueAccounts as $account)
                                                <option value="{{ $account->account_code }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <strong>Line Total: $<span class="line-total">0.00</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-info mb-3" id="add-item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Invoice Totals</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <strong>$<span id="subtotal">0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Tax (15%):</span>
                                            <strong>$<span id="tax-amount">0.00</span></strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span><strong>Total:</strong></span>
                                            <strong>$<span id="total">0.00</span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Invoice
                            </button>
                            <a href="{{ route('finance.accounts-receivable.invoices.index') }}" class="btn btn-secondary">
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
let itemCount = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const itemsContainer = document.getElementById('invoice-items');
    const newItem = document.createElement('div');
    newItem.className = 'invoice-item mb-3';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label>Description *</label>
                <input type="text" class="form-control" name="items[${itemCount}][description]" required>
            </div>
            <div class="col-md-2">
                <label>Quantity *</label>
                <input type="number" class="form-control item-quantity" name="items[${itemCount}][quantity]" step="0.01" min="0.01" required>
            </div>
            <div class="col-md-2">
                <label>Unit Price *</label>
                <input type="number" class="form-control item-price" name="items[${itemCount}][unit_price]" step="0.01" min="0" required>
            </div>
            <div class="col-md-3">
                <label>Revenue Account *</label>
                <select class="form-control" name="items[${itemCount}][account_code]" required>
                    <option value="">Select Account</option>
                    @foreach($revenueAccounts as $account)
                        <option value="{{ $account->account_code }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <strong>Line Total: $<span class="line-total">0.00</span></strong>
            </div>
        </div>
    `;
    itemsContainer.appendChild(newItem);
    itemCount++;
    updateRemoveButtons();
    attachItemListeners();
});

function updateRemoveButtons() {
    const items = document.querySelectorAll('.invoice-item');
    const removeButtons = document.querySelectorAll('.remove-item');
    
    removeButtons.forEach((button, index) => {
        button.style.display = items.length > 1 ? 'block' : 'none';
        button.onclick = function() {
            items[index].remove();
            updateRemoveButtons();
            calculateTotals();
        };
    });
}

function attachItemListeners() {
    const quantityInputs = document.querySelectorAll('.item-quantity');
    const priceInputs = document.querySelectorAll('.item-price');
    
    quantityInputs.forEach(input => {
        input.removeEventListener('input', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });
    
    priceInputs.forEach(input => {
        input.removeEventListener('input', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });
}

function calculateTotals() {
    const items = document.querySelectorAll('.invoice-item');
    let subtotal = 0;
    
    items.forEach(item => {
        const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(item.querySelector('.item-price').value) || 0;
        const lineTotal = quantity * price;
        
        item.querySelector('.line-total').textContent = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    
    const taxAmount = subtotal * 0.15;
    const total = subtotal + taxAmount;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax-amount').textContent = taxAmount.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// Initialize
updateRemoveButtons();
attachItemListeners();
calculateTotals();
</script>
@endsection
