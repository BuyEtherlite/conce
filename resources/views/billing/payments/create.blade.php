@extends('layouts.admin')

@section('page-title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💳 Record Payment</h4>
        <a href="{{ route('billing.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Payments
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('billing.payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bill_id" class="form-label">Select Bill *</label>
                                    <select class="form-select" id="bill_id" name="bill_id" required>
                                        <option value="">Choose a bill...</option>
                                        {{-- Bills will be loaded here --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method *</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Select method...</option>
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="card">Credit/Debit Card</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Payment Amount *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date *</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" id="reference_number" name="reference_number" 
                                           placeholder="Cheque/Transaction reference">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="received_by" class="form-label">Received By</label>
                                    <input type="text" class="form-control" id="received_by" name="received_by" 
                                           value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Additional payment notes..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('billing.payments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Bill Information</h6>
                </div>
                <div class="card-body">
                    <div id="bill-details" class="text-muted">
                        <p>Select a bill to view details</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Payment Methods Guide</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Cash:</strong> Physical cash payments<br>
                        <strong>Cheque:</strong> Bank cheque payments<br>
                        <strong>Bank Transfer:</strong> Direct bank transfers<br>
                        <strong>Mobile Money:</strong> EcoCash, OneMoney, etc.<br>
                        <strong>Card:</strong> Credit or debit card payments
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const billSelect = document.getElementById('bill_id');
    const amountInput = document.getElementById('amount');
    const billDetails = document.getElementById('bill-details');

    billSelect.addEventListener('change', function() {
        if (this.value) {
            // Here you would typically fetch bill details via AJAX
            billDetails.innerHTML = `
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Loading bill details...</span>
            `;
            
            // Simulate loading bill details
            setTimeout(() => {
                billDetails.innerHTML = `
                    <p><strong>Bill #:</strong> BILL-2024-000${this.value}</p>
                    <p><strong>Customer:</strong> Sample Customer</p>
                    <p><strong>Amount Due:</strong> $250.00</p>
                    <p><strong>Due Date:</strong> ${new Date().toLocaleDateString()}</p>
                `;
                amountInput.value = '250.00';
            }, 1000);
        } else {
            billDetails.innerHTML = '<p class="text-muted">Select a bill to view details</p>';
            amountInput.value = '';
        }
    });
});
</script>
@endsection
