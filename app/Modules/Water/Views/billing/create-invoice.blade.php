@extends('layouts.admin')

@section('page-title', 'Create Water Invoice')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Create Water Invoice</h4>
        <a href="{{ route('water.billing.invoices') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Invoices
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Invoice Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('water.billing.invoices.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="connection_id" class="form-label">Water Connection <span class="text-danger">*</span></label>
                            <select class="form-select" id="connection_id" name="connection_id" required>
                                <option value="">Select Connection</option>
                                <option value="1">WC-001 - John Smith (M-001234)</option>
                                <option value="2">WC-002 - ABC Corporation (M-001235)</option>
                                <option value="3">WC-003 - XYZ Restaurant (M-001236)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="billing_period" class="form-label">Billing Period <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" id="billing_period" name="billing_period" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="previous_reading" class="form-label">Previous Reading</label>
                            <input type="number" class="form-control" id="previous_reading" name="previous_reading" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="current_reading" class="form-label">Current Reading <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="current_reading" name="current_reading" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="usage" class="form-label">Usage (Gallons)</label>
                            <input type="number" class="form-control" id="usage" name="usage" step="0.01" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rate_per_gallon" class="form-label">Rate per Gallon ($)</label>
                            <input type="number" class="form-control" id="rate_per_gallon" name="rate_per_gallon" step="0.001" value="0.035">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="base_charge" class="form-label">Base Charge ($)</label>
                            <input type="number" class="form-control" id="base_charge" name="base_charge" step="0.01" value="15.00">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previousReading = document.getElementById('previous_reading');
    const currentReading = document.getElementById('current_reading');
    const usage = document.getElementById('usage');

    function calculateUsage() {
        const prev = parseFloat(previousReading.value) || 0;
        const current = parseFloat(currentReading.value) || 0;
        const usageValue = Math.max(0, current - prev);
        usage.value = usageValue.toFixed(2);
    }

    previousReading.addEventListener('input', calculateUsage);
    currentReading.addEventListener('input', calculateUsage);
});
</script>
@endsection
