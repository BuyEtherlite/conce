@extends('layouts.admin')

@section('page-title', 'Create Purchase Order')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🛒 Create New Purchase Order</h4>
        <a href="{{ route('finance.procurement.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Procurement
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
            <h6 class="m-0">Purchase Order Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('finance.procurement.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order_number" class="form-label">Order Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('order_number') is-invalid @enderror" 
                                   id="order_number" name="order_number" value="{{ old('order_number', 'PO-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" required>
                            @error('order_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" name="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order_date" class="form-label">Order Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('order_date') is-invalid @enderror" 
                                   id="order_date" name="order_date" 
                                   value="{{ old('order_date', date('Y-m-d')) }}" required>
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Expected Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" name="delivery_date" 
                                   value="{{ old('delivery_date') }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Order Items <span class="text-danger">*</span></label>
                    <div id="items-container">
                        <div class="item-row border p-3 mb-2 rounded">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="items[0][item_id]" class="form-select" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][quantity]" class="form-control" 
                                           placeholder="Quantity" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][unit_price]" class="form-control" 
                                           placeholder="Unit Price" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control item-total" placeholder="Total" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" min="0" max="100" 
                                   class="form-control @error('tax_rate') is-invalid @enderror" 
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Order Summary</label>
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal:</span>
                                        <span id="subtotal-display">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Tax:</span>
                                        <span id="tax-display">$0.00</span>
                                    </div>
                                    <hr class="my-1">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span id="total-display">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3" 
                              placeholder="Additional notes or instructions">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('finance.procurement.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    function updateCalculations() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-row').forEach(function(row) {
            const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            const total = quantity * unitPrice;
            
            row.querySelector('.item-total').value = total.toFixed(2);
            subtotal += total;
        });
        
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const totalAmount = subtotal + taxAmount;
        
        document.getElementById('subtotal-display').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax-display').textContent = '$' + taxAmount.toFixed(2);
        document.getElementById('total-display').textContent = '$' + totalAmount.toFixed(2);
    }
    
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.querySelector('.item-row').cloneNode(true);
        
        // Update input names
        newRow.querySelectorAll('input, select').forEach(function(input) {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[0\]/, '[' + itemIndex + ']'));
                input.value = '';
            }
        });
        
        // Show remove button
        newRow.querySelector('.remove-item').style.display = 'block';
        
        container.appendChild(newRow);
        itemIndex++;
        
        updateRemoveButtons();
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
            updateCalculations();
            updateRemoveButtons();
        }
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="[quantity]"], input[name*="[unit_price]"], #tax_rate')) {
            updateCalculations();
        }
    });
    
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(function(row, index) {
            const removeBtn = row.querySelector('.remove-item');
            if (rows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
