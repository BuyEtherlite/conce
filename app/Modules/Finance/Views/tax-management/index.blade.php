@extends('layouts.admin')

@section('page-title', 'Tax Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📋 Tax Management</h4>
        <div>
            <a href="{{ route('finance.tax-management.rates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Tax Rate
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <!-- Tax Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>${{ number_format($taxSummary['monthly_vat_collected'], 2) }}</h5>
                            <p class="card-text">Monthly VAT Collected</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>${{ number_format($taxSummary['quarterly_vat_payable'], 2) }}</h5>
                            <p class="card-text">Quarterly VAT Payable</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>${{ number_format($taxSummary['withholding_tax_current'], 2) }}</h5>
                            <p class="card-text">Current Withholding Tax</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>{{ \Carbon\Carbon::parse($taxSummary['next_filing_deadline'])->format('M d, Y') }}</h5>
                            <p class="card-text">Next Filing Deadline</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.tax-management.rates.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-percentage me-2"></i>Manage Tax Rates
                        </a>
                        <a href="{{ route('finance.tax-management.reports') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Tax Reports
                        </a>
                        <a href="{{ route('finance.tax-management.filing') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-upload me-2"></i>Tax Filing
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="showVATCalculator()">
                            <i class="fas fa-calculator me-2"></i>VAT Calculator
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tax Rates -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6><i class="fas fa-list me-2"></i>Active Tax Rates</h6>
                    <a href="{{ route('finance.tax-management.rates.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Rate</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($taxRates->take(5) as $rate)
                                <tr>
                                    <td>
                                        {{ $rate->name }}
                                        @if($rate->is_default)
                                            <span class="badge bg-primary ms-1">Default</span>
                                        @endif
                                    </td>
                                    <td>{{ $rate->code }}</td>
                                    <td>
                                        {{ $rate->rate }}{{ $rate->type === 'percentage' ? '%' : '' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($rate->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $rate->is_active ? 'success' : 'danger' }}">
                                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('finance.tax-management.rates.edit', $rate) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tax Transactions -->
    <div class="card mt-4">
        <div class="card-header">
            <h6><i class="fas fa-history me-2"></i>Recent Tax Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Tax Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ now()->format('Y-m-d') }}</td>
                            <td>VAT Collection</td>
                            <td>INV-2025-001</td>
                            <td>${{ number_format(2500, 2) }}</td>
                            <td><span class="badge bg-success">Collected</span></td>
                        </tr>
                        <tr>
                            <td>{{ now()->subDay()->format('Y-m-d') }}</td>
                            <td>Withholding Tax</td>
                            <td>PAY-2025-001</td>
                            <td>${{ number_format(150, 2) }}</td>
                            <td><span class="badge bg-info">Withheld</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- VAT Calculator Modal -->
<div class="modal fade" id="vatCalculatorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">VAT Calculator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="vatCalculatorForm">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="tax_rate_id" class="form-label">Tax Rate</label>
                        <select class="form-control" id="tax_rate_id" required>
                            <option value="">Select Tax Rate</option>
                            @foreach($taxRates as $rate)
                                <option value="{{ $rate->id }}">{{ $rate->name }} ({{ $rate->rate }}{{ $rate->type === 'percentage' ? '%' : '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Calculate</button>
                </form>
                
                <div id="calculationResult" class="mt-3" style="display: none;">
                    <h6>Calculation Result:</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Base Amount:</td>
                            <td id="baseAmount"></td>
                        </tr>
                        <tr>
                            <td>Tax Amount:</td>
                            <td id="taxAmount"></td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total Amount:</td>
                            <td id="totalAmount"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showVATCalculator() {
    $('#vatCalculatorModal').modal('show');
}

document.getElementById('vatCalculatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amount = document.getElementById('amount').value;
    const taxRateId = document.getElementById('tax_rate_id').value;
    
    fetch('/finance/tax-management/calculate-vat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            amount: amount,
            tax_rate_id: taxRateId
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('baseAmount').textContent = '$' + parseFloat(data.base_amount).toFixed(2);
        document.getElementById('taxAmount').textContent = '$' + parseFloat(data.tax_amount).toFixed(2);
        document.getElementById('totalAmount').textContent = '$' + parseFloat(data.total_amount).toFixed(2);
        document.getElementById('calculationResult').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calculating VAT');
    });
});
</script>
@endsection
