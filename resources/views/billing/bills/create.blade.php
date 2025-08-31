@extends('layouts.admin')

@section('title', 'Create New Bill')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-file-earmark-plus"></i> Create New Bill</h2>
                <a href="{{ route('billing.bills.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Bills
                </a>
            </div>

            <form action="{{ route('billing.bills.store') }}" method="POST" id="billForm">
                @csrf
                
                <!-- Customer Selection -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_account_id" class="form-label">Customer Account *</label>
                                    <select name="customer_account_id" id="customer_account_id" class="form-select @error('customer_account_id') is-invalid @enderror" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_account_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }} ({{ $customer->account_number }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('customer_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_period" class="form-label">Billing Period *</label>
                                    <input type="text" name="billing_period" id="billing_period" class="form-control @error('billing_period') is-invalid @enderror" value="{{ old('billing_period', date('F Y')) }}" required>
                                    @error('billing_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date *</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                    @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Discount Amount</label>
                                    <input type="number" name="discount_amount" id="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" step="0.01" min="0" value="{{ old('discount_amount', 0) }}">
                                    @error('discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Services & Line Items</h5>
                    </div>
                    <div class="card-body">
                        <div id="services-container">
                            <div class="service-item border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Service *</label>
                                        <select name="services[0][service_id]" class="form-select service-select" required>
                                            <option value="">Select Service</option>
                                            @foreach($services ?? [] as $service)
                                            <option value="{{ $service->id }}" data-rate="{{ $service->base_rate }}">
                                                {{ $service->name }} (${{ number_format($service->base_rate, 2) }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" name="services[0][quantity]" class="form-control quantity-input" step="0.01" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Custom Rate</label>
                                        <input type="number" name="services[0][custom_rate]" class="form-control rate-input" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Amount</label>
                                        <input type="text" class="form-control amount-display" readonly>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-service" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary" id="add-service">
                            <i class="bi bi-plus"></i> Add Service
                        </button>
                    </div>
                </div>

                <!-- Bill Summary -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Bill Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td class="text-end" id="subtotal-display">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tax:</td>
                                        <td class="text-end" id="tax-display">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Discount:</td>
                                        <td class="text-end" id="discount-display">$0.00</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total:</td>
                                        <td class="text-end" id="total-display">$0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('billing.bills.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = 1;

    // Add service functionality
    document.getElementById('add-service').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const newService = container.children[0].cloneNode(true);
        
        // Update names and reset values
        newService.querySelectorAll('select, input').forEach(function(element) {
            const name = element.name;
            if (name) {
                element.name = name.replace(/\[0\]/, `[${serviceIndex}]`);
                if (element.type !== 'select-one') {
                    element.value = element.type === 'number' && element.classList.contains('quantity-input') ? '1' : '';
                }
            }
        });
        
        newService.querySelector('.remove-service').disabled = false;
        container.appendChild(newService);
        serviceIndex++;
        
        updateRemoveButtons();
        attachServiceEventListeners(newService);
    });

    // Remove service functionality
    function updateRemoveButtons() {
        const services = document.querySelectorAll('.service-item');
        services.forEach(function(service, index) {
            const removeBtn = service.querySelector('.remove-service');
            removeBtn.disabled = services.length === 1;
        });
    }

    // Attach event listeners to service items
    function attachServiceEventListeners(serviceItem) {
        const serviceSelect = serviceItem.querySelector('.service-select');
        const quantityInput = serviceItem.querySelector('.quantity-input');
        const rateInput = serviceItem.querySelector('.rate-input');
        const amountDisplay = serviceItem.querySelector('.amount-display');
        const removeBtn = serviceItem.querySelector('.remove-service');

        function updateAmount() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const baseRate = selectedOption.dataset.rate || 0;
            const customRate = rateInput.value || baseRate;
            const quantity = quantityInput.value || 1;
            const amount = customRate * quantity;
            
            amountDisplay.value = '$' + amount.toFixed(2);
            updateTotals();
        }

        serviceSelect.addEventListener('change', updateAmount);
        quantityInput.addEventListener('input', updateAmount);
        rateInput.addEventListener('input', updateAmount);

        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.service-item').length > 1) {
                serviceItem.remove();
                updateRemoveButtons();
                updateTotals();
            }
        });
    }

    // Update bill totals
    function updateTotals() {
        let subtotal = 0;
        let tax = 0;
        
        document.querySelectorAll('.service-item').forEach(function(item) {
            const serviceSelect = item.querySelector('.service-select');
            const quantityInput = item.querySelector('.quantity-input');
            const rateInput = item.querySelector('.rate-input');
            
            if (serviceSelect.value && quantityInput.value) {
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const baseRate = parseFloat(selectedOption.dataset.rate) || 0;
                const customRate = parseFloat(rateInput.value) || baseRate;
                const quantity = parseFloat(quantityInput.value) || 1;
                const amount = customRate * quantity;
                
                subtotal += amount;
                // Assuming 15% tax for taxable services
                tax += amount * 0.15; // You can make this more sophisticated
            }
        });
        
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const total = subtotal + tax - discount;
        
        document.getElementById('subtotal-display').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax-display').textContent = '$' + tax.toFixed(2);
        document.getElementById('discount-display').textContent = '$' + discount.toFixed(2);
        document.getElementById('total-display').textContent = '$' + total.toFixed(2);
    }

    // Initialize event listeners for existing service items
    document.querySelectorAll('.service-item').forEach(attachServiceEventListeners);
    
    // Update totals when discount changes
    document.getElementById('discount_amount').addEventListener('input', updateTotals);
});
</script>
@endsection
