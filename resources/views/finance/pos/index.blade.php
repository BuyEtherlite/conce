@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Point of Sale - Collections</h1>
        <div class="btn-group">
            <button class="btn btn-primary" id="newCollectionBtn">
                <i class="fas fa-plus me-2"></i>New Collection
            </button>
            <a href="{{ route('finance.pos.daily-report') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Daily Report
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Collections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                R{{ number_format($stats['today_collections'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['today_transactions']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Terminals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_terminals'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_customers']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Collections -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Recent Collections</h6>
            <button class="btn btn-sm btn-primary" id="refreshCollections">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="collectionsTable">
                    <thead>
                        <tr>
                            <th>Receipt#</th>
                            <th>Date/Time</th>
                            <th>Customer</th>
                            <th>Account#</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Terminal</th>
                            <th>Collector</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCollections as $collection)
                        <tr>
                            <td>{{ $collection->receipt_number }}</td>
                            <td>{{ $collection->collected_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $collection->customerAccount->customer->name }}</td>
                            <td>{{ $collection->customerAccount->account_number }}</td>
                            <td>R{{ number_format($collection->amount_paid, 2) }}</td>
                            <td>{{ $collection->paymentMethod->name }}</td>
                            <td>{{ $collection->posTerminal->name }}</td>
                            <td>{{ $collection->collector->name }}</td>
                            <td>
                                <a href="{{ route('finance.pos.receipt', $collection->id) }}" 
                                   class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-print"></i>
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

<!-- Collection Modal -->
<div class="modal fade" id="collectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Payment Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Customer Search -->
                <div class="mb-3">
                    <label class="form-label">Search Customer</label>
                    <input type="text" class="form-control" id="customerSearch" 
                           placeholder="Enter account number, meter number, or customer name">
                    <div id="customerResults" class="mt-2"></div>
                </div>

                <!-- Customer Details -->
                <div id="customerDetails" class="d-none">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Customer Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <span id="customerName"></span></p>
                                    <p><strong>Account:</strong> <span id="accountNumber"></span></p>
                                    <p><strong>Account Type:</strong> <span id="accountType"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Current Balance:</strong> R<span id="currentBalance"></span></p>
                                    <p><strong>Amount Owing:</strong> <span class="text-danger fw-bold">R<span id="amountOwing"></span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="paymentForm" class="d-none">
                    <input type="hidden" id="customerAccountId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethodId" required>
                                <option value="">Select payment method</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">POS Terminal</label>
                            <select class="form-select" id="posTerminalId" required>
                                <option value="">Select terminal</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount to Pay</label>
                            <input type="number" class="form-control" id="amountPaid" 
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control" id="referenceNumber">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="2"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Collect Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load payment methods and terminals
    loadPaymentOptions();
    
    // New collection button
    document.getElementById('newCollectionBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('collectionModal'));
        modal.show();
    });

    // Customer search
    let searchTimeout;
    document.getElementById('customerSearch').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 3) {
            searchTimeout = setTimeout(() => searchCustomers(query), 500);
        } else {
            document.getElementById('customerResults').innerHTML = '';
            hideCustomerDetails();
        }
    });

    // Payment form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        collectPayment();
    });

    // Refresh collections
    document.getElementById('refreshCollections').addEventListener('click', function() {
        location.reload();
    });
});

function loadPaymentOptions() {
    // Load payment methods
    fetch('/finance/api/payment-methods')
        .then(response => response.json())
        .then(methods => {
            const select = document.getElementById('paymentMethodId');
            select.innerHTML = '<option value="">Select payment method</option>';
            methods.forEach(method => {
                select.innerHTML += `<option value="${method.id}">${method.name}</option>`;
            });
        });

    // Load POS terminals
    fetch('/finance/api/pos-terminals')
        .then(response => response.json())
        .then(terminals => {
            const select = document.getElementById('posTerminalId');
            select.innerHTML = '<option value="">Select terminal</option>';
            terminals.forEach(terminal => {
                select.innerHTML += `<option value="${terminal.id}">${terminal.name} - ${terminal.location}</option>`;
            });
        });
}

function searchCustomers(query) {
    fetch(`/finance/pos/search-customer?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(customers => {
            let html = '';
            if (customers.length > 0) {
                html = '<div class="list-group">';
                customers.forEach(customer => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action customer-item" 
                           data-id="${customer.id}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${customer.customer.name}</h6>
                                <small class="text-danger">R${parseFloat(customer.current_balance).toFixed(2)}</small>
                            </div>
                            <p class="mb-1">${customer.account_number} - ${customer.account_type.name}</p>
                            <small>Meter: ${customer.meter_number || 'N/A'}</small>
                        </a>
                    `;
                });
                html += '</div>';
            } else {
                html = '<div class="alert alert-info">No customers found</div>';
            }
            
            document.getElementById('customerResults').innerHTML = html;
            
            // Add click handlers
            document.querySelectorAll('.customer-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    selectCustomer(this.dataset.id);
                });
            });
        });
}

function selectCustomer(customerId) {
    fetch(`/finance/pos/customer/${customerId}`)
        .then(response => response.json())
        .then(data => {
            showCustomerDetails(data.account, data.owing);
            document.getElementById('customerAccountId').value = customerId;
            document.getElementById('customerResults').innerHTML = '';
            document.getElementById('paymentForm').classList.remove('d-none');
        });
}

function showCustomerDetails(account, owing) {
    document.getElementById('customerName').textContent = account.customer.name;
    document.getElementById('accountNumber').textContent = account.account_number;
    document.getElementById('accountType').textContent = account.account_type.name;
    document.getElementById('currentBalance').textContent = parseFloat(account.current_balance).toFixed(2);
    document.getElementById('amountOwing').textContent = parseFloat(owing).toFixed(2);
    
    document.getElementById('customerDetails').classList.remove('d-none');
    document.getElementById('amountPaid').setAttribute('max', owing);
}

function hideCustomerDetails() {
    document.getElementById('customerDetails').classList.add('d-none');
    document.getElementById('paymentForm').classList.add('d-none');
}

function collectPayment() {
    const formData = {
        customer_account_id: document.getElementById('customerAccountId').value,
        payment_method_id: document.getElementById('paymentMethodId').value,
        pos_terminal_id: document.getElementById('posTerminalId').value,
        amount_paid: document.getElementById('amountPaid').value,
        reference_number: document.getElementById('referenceNumber').value,
        description: document.getElementById('description').value
    };

    fetch('/finance/pos/collect-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment collected successfully! Receipt: ' + data.receipt_number);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error collecting payment: ' + error.message);
    });
}
</script>
@endsection
