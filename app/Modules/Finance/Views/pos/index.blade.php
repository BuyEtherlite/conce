@extends('layouts.admin')

@section('title', 'Smart POS System')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Smart POS System</h1>
        <div class="btn-group">
            <button class="btn btn-success" id="newCollectionBtn">
                <i class="fas fa-plus me-2"></i>New Payment
            </button>
            <a href="{{ route('finance.pos.daily-report') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Daily Report
            </a>
            <button class="btn btn-primary" id="analyticsBtn">
                <i class="fas fa-analytics me-2"></i>Analytics
            </button>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todayRevenue">$0.00</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todayTransactions">0</div>
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
                                Average Transaction</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgTransaction">$0.00</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                Active Terminal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeTerminal">
                                {{ $terminals->first()->terminal_name ?? 'None' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Enhanced Bill Lookup Panel -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-search me-2"></i>Advanced Bill Lookup
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="searchType" class="form-label">Search By</label>
                            <select id="searchType" class="form-control">
                                <option value="customer_name">Customer Name</option>
                                <option value="meter_number">Meter Number</option>
                                <option value="address">Address</option>
                                <option value="account_number">Account Number</option>
                                <option value="qr_code">QR Code</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="searchValue" class="form-label">Search Value</label>
                            <div class="position-relative">
                                <input type="text" id="searchValue" class="form-control" 
                                       placeholder="Enter search terms..." autocomplete="off">
                                <div id="customerSuggestions" class="dropdown-menu" style="width: 100%; max-height: 200px; overflow-y: auto;"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button id="searchBtn" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <button id="qrScanBtn" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-qrcode me-1"></i>Scan QR Code
                            </button>
                            <button id="generateQrBtn" class="btn btn-outline-info me-2">
                                <i class="fas fa-plus me-1"></i>Generate QR Code
                            </button>
                            <button id="clearSearchBtn" class="btn btn-outline-warning">
                                <i class="fas fa-broom me-1"></i>Clear
                            </button>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="searchResults" class="mt-4" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Search Results (<span id="resultsCount">0</span> found)</h6>
                            <button id="selectAllResultsBtn" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-square me-1"></i>Select All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%"><input type="checkbox" id="selectAll"></th>
                                        <th width="15%">Bill Number</th>
                                        <th width="20%">Customer</th>
                                        <th width="10%">Type</th>
                                        <th width="12%">Amount</th>
                                        <th width="12%">Due Date</th>
                                        <th width="10%">Status</th>
                                        <th width="16%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsTableBody">
                                    <!-- Results will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Payment Panel -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-cash-register me-2"></i>Payment Processing
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Terminal Selection -->
                    <div class="mb-3">
                        <label for="terminalSelect" class="form-label">POS Terminal</label>
                        <select id="terminalSelect" class="form-control">
                            @foreach($terminals as $terminal)
                                <option value="{{ $terminal->id }}">{{ $terminal->terminal_name }} ({{ $terminal->location }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selected Bills -->
                    <div id="selectedBills" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Selected Bills</h6>
                            <button id="clearSelectedBtn" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times me-1"></i>Clear All
                            </button>
                        </div>
                        <div id="selectedBillsList" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                            <!-- Selected bills will appear here -->
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select id="paymentMethod" class="form-control">
                            <option value="cash">💵 Cash</option>
                            <option value="card">💳 Credit/Debit Card</option>
                            <option value="mobile_money">📱 Mobile Money</option>
                            <option value="bank_transfer">🏦 Bank Transfer</option>
                            <option value="cheque">📜 Cheque</option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="totalAmount" class="form-control form-control-lg text-right font-weight-bold" readonly>
                        </div>
                    </div>

                    <!-- Process Payment Button -->
                    <button id="processPaymentBtn" class="btn btn-success btn-lg w-100 mb-3" disabled>
                        <i class="fas fa-credit-card me-1"></i>Process Payment
                    </button>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-6">
                            <button id="quickCashBtn" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-money-bill me-1"></i>Quick Cash
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="loyaltyBtn" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-star me-1"></i>Loyalty
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Session Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded mb-2">
                                <h6 class="mb-0" id="sessionTransactions">0</h6>
                                <small class="text-muted">Transactions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded mb-2">
                                <h6 class="mb-0" id="sessionRevenue">$0.00</h6>
                                <small class="text-muted">Revenue</small>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <h6 class="mb-0" id="cashPayments">0</h6>
                                <small class="text-muted">Cash</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <h6 class="mb-0" id="cardPayments">0</h6>
                                <small class="text-muted">Card</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

<!-- Enhanced Modals for Smart POS System -->

<!-- QR Code Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code Scanner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrReader" style="width: 100%; height: 300px; border: 1px solid #ddd;"></div>
                <div class="mt-3">
                    <button id="startScan" class="btn btn-primary">Start Scanning</button>
                    <button id="stopScan" class="btn btn-secondary">Stop Scanning</button>
                </div>
                <p class="mt-2 text-muted">Position the QR code within the camera view</p>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Generator Modal -->
<div class="modal fade" id="qrGeneratorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="qrBillType" class="form-label">Bill Type</label>
                    <select id="qrBillType" class="form-control">
                        <option value="invoice">Invoice</option>
                        <option value="water_bill">Water Bill</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="qrBillId" class="form-label">Bill ID</label>
                    <input type="number" id="qrBillId" class="form-control" placeholder="Enter bill ID">
                </div>
                <div id="qrCodeDisplay" class="text-center mt-3" style="display: none;">
                    <div id="qrCodeImage"></div>
                    <p class="mt-2"><strong>QR Data:</strong> <span id="qrCodeData"></span></p>
                    <button class="btn btn-sm btn-secondary" onclick="downloadQR()">
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="generateQrCodeBtn" class="btn btn-primary">Generate QR Code</button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="receiptContent" class="font-monospace"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="printReceiptBtn" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i>Print Receipt
                </button>
                <button type="button" id="emailReceiptBtn" class="btn btn-info">
                    <i class="fas fa-envelope me-1"></i>Email Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loyalty Points Modal -->
<div class="modal fade" id="loyaltyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Loyalty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="loyaltyInfo">
                    <!-- Loyalty information will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .dropdown-menu {
        display: none;
        position: absolute;
        z-index: 1000;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .dropdown-menu.show {
        display: block;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    #receiptContent {
        white-space: pre-line;
        background-color: #f8f9fa;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        min-height: 300px;
    }
    
    .text-right {
        text-align: right;
    }
    
    .selected-bill-item {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .bill-checkbox:checked + label {
        background-color: #e3f2fd;
    }
</style>

<script>
// Enhanced POS System JavaScript
$(document).ready(function() {
    let selectedBills = [];
    let customerSuggestionsTimeout;
    let sessionStats = {
        transactions: 0,
        revenue: 0,
        cashPayments: 0,
        cardPayments: 0
    };

    // Initialize
    loadTodayStats();
    initializeEventHandlers();

    function initializeEventHandlers() {
        // Search functionality
        $('#searchType').change(updateSearchPlaceholder);
        $('#searchValue').on('input', handleSearchInput);
        $('#searchBtn').click(performBillLookup);
        $('#searchValue').keypress(function(e) {
            if (e.which === 13) performBillLookup();
        });

        // Selection functionality
        $('#selectAll').change(handleSelectAll);
        $('#selectAllResultsBtn').click(selectAllResults);
        $('#clearSelectedBtn').click(clearSelectedBills);
        $('#clearSearchBtn').click(clearSearch);

        // Payment processing
        $('#processPaymentBtn').click(processPayment);
        $('#quickCashBtn').click(function() {
            $('#paymentMethod').val('cash');
        });

        // Modal handlers
        $('#qrScanBtn').click(() => $('#qrScannerModal').modal('show'));
        $('#generateQrBtn').click(() => $('#qrGeneratorModal').modal('show'));
        $('#generateQrCodeBtn').click(generateQrCode);
        $('#loyaltyBtn').click(() => $('#loyaltyModal').modal('show'));

        // Receipt handlers
        $('#printReceiptBtn').click(printReceipt);
        $('#emailReceiptBtn').click(emailReceipt);
    }

    function updateSearchPlaceholder() {
        const searchType = $('#searchType').val();
        const placeholders = {
            'customer_name': 'Enter customer name...',
            'meter_number': 'Enter meter number...',
            'address': 'Enter address...',
            'account_number': 'Enter account number...',
            'qr_code': 'Enter QR code data...'
        };
        $('#searchValue').attr('placeholder', placeholders[searchType] || 'Enter search terms...');
    }

    function handleSearchInput() {
        const value = $(this).val();
        const searchType = $('#searchType').val();
        
        if (searchType === 'customer_name' && value.length >= 2) {
            clearTimeout(customerSuggestionsTimeout);
            customerSuggestionsTimeout = setTimeout(() => {
                loadCustomerSuggestions(value);
            }, 300);
        } else {
            $('#customerSuggestions').removeClass('show');
        }
    }

    function loadCustomerSuggestions(query) {
        $.get('/api/finance/pos/customer-suggestions', {q: query})
            .done(function(customers) {
                const suggestionsDiv = $('#customerSuggestions');
                suggestionsDiv.empty();
                
                if (customers.length > 0) {
                    customers.forEach(customer => {
                        const item = $(`<div class="dropdown-item" data-value="${customer.name}">
                            <strong>${customer.name}</strong><br>
                            <small class="text-muted">${customer.code} - ${customer.address}</small>
                        </div>`);
                        
                        item.click(function() {
                            $('#searchValue').val(customer.name);
                            suggestionsDiv.removeClass('show');
                            performBillLookup();
                        });
                        
                        suggestionsDiv.append(item);
                    });
                    suggestionsDiv.addClass('show');
                } else {
                    suggestionsDiv.removeClass('show');
                }
            });
    }

    function performBillLookup() {
        const searchType = $('#searchType').val();
        const searchValue = $('#searchValue').val().trim();
        
        if (!searchValue) {
            showAlert('Please enter a search value', 'warning');
            return;
        }

        showLoading('Searching bills...');
        
        $.post('/api/finance/pos/bill-lookup', {
            search_type: searchType,
            search_value: searchValue,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(response) {
            hideLoading();
            if (response.success) {
                displaySearchResults(response.bills);
                $('#resultsCount').text(response.total_found);
            } else {
                showAlert(response.error || 'Search failed', 'error');
            }
        })
        .fail(function() {
            hideLoading();
            showAlert('Search request failed', 'error');
        });
    }

    function displaySearchResults(bills) {
        const tbody = $('#resultsTableBody');
        tbody.empty();
        
        if (bills.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center text-muted">No bills found</td></tr>');
            $('#searchResults').show();
            return;
        }

        bills.forEach(bill => {
            const statusBadge = getStatusBadge(bill.status);
            const typeIcon = getTypeIcon(bill.type);
            const row = $(`
                <tr>
                    <td><input type="checkbox" class="bill-checkbox" data-bill='${JSON.stringify(bill)}'></td>
                    <td><code>${bill.bill_number}</code></td>
                    <td>
                        <strong>${bill.customer_name}</strong>
                        ${bill.address ? '<br><small class="text-muted">' + bill.address + '</small>' : ''}
                    </td>
                    <td>${typeIcon} <span class="badge bg-info">${bill.type.replace('_', ' ').toUpperCase()}</span></td>
                    <td><strong>$${parseFloat(bill.amount).toFixed(2)}</strong></td>
                    <td>${bill.due_date || '<span class="text-muted">N/A</span>'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-primary select-bill-btn" data-bill='${JSON.stringify(bill)}'>
                            <i class="fas fa-plus me-1"></i>Select
                        </button>
                    </td>
                </tr>
            `);
            tbody.append(row);
        });

        // Bind events
        $('.bill-checkbox').change(updateSelectedBills);
        $('.select-bill-btn').click(function() {
            const bill = JSON.parse($(this).data('bill'));
            const checkbox = $(this).closest('tr').find('.bill-checkbox');
            checkbox.prop('checked', !checkbox.is(':checked'));
            updateSelectedBills();
        });

        $('#searchResults').show();
    }

    function updateSelectedBills() {
        selectedBills = [];
        $('.bill-checkbox:checked').each(function() {
            selectedBills.push(JSON.parse($(this).data('bill')));
        });

        if (selectedBills.length > 0) {
            displaySelectedBills();
            $('#selectedBills').show();
            $('#processPaymentBtn').prop('disabled', false);
        } else {
            $('#selectedBills').hide();
            $('#processPaymentBtn').prop('disabled', true);
        }

        updateTotalAmount();
    }

    function displaySelectedBills() {
        const listDiv = $('#selectedBillsList');
        listDiv.empty();

        selectedBills.forEach((bill, index) => {
            const item = $(`
                <div class="selected-bill-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${bill.bill_number}</strong><br>
                        <small class="text-muted">${bill.customer_name}</small>
                    </div>
                    <div class="text-right">
                        <div><strong>$${parseFloat(bill.amount).toFixed(2)}</strong></div>
                        <button class="btn btn-sm btn-outline-danger remove-bill-btn" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `);
            listDiv.append(item);
        });

        // Bind remove events
        $('.remove-bill-btn').click(function() {
            const index = $(this).data('index');
            selectedBills.splice(index, 1);
            
            // Uncheck corresponding checkbox
            $('.bill-checkbox').each(function() {
                const bill = JSON.parse($(this).data('bill'));
                if (selectedBills.findIndex(sb => sb.id === bill.id && sb.type === bill.type) === -1) {
                    $(this).prop('checked', false);
                }
            });
            
            updateSelectedBills();
        });
    }

    function updateTotalAmount() {
        const total = selectedBills.reduce((sum, bill) => sum + parseFloat(bill.amount), 0);
        $('#totalAmount').val(total.toFixed(2));
    }

    function processPayment() {
        if (selectedBills.length === 0) {
            showAlert('Please select bills to pay', 'warning');
            return;
        }

        const terminalId = $('#terminalSelect').val();
        const paymentMethod = $('#paymentMethod').val();
        const totalAmount = $('#totalAmount').val();

        if (!terminalId) {
            showAlert('Please select a POS terminal', 'warning');
            return;
        }

        showLoading('Processing payment...');

        $.post('/api/finance/pos/process-payment', {
            bills: selectedBills,
            payment_method: paymentMethod,
            terminal_id: terminalId,
            total_amount: totalAmount,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(response) {
            hideLoading();
            if (response.success) {
                showAlert('Payment processed successfully!', 'success');
                displayReceipt(response.receipt);
                clearSelections();
                updateSessionStats();
                loadTodayStats();
            } else {
                showAlert(response.error || 'Payment processing failed', 'error');
            }
        })
        .fail(function() {
            hideLoading();
            showAlert('Payment request failed', 'error');
        });
    }

    function displayReceipt(receipt) {
        // Generate receipt content if not provided
        if (receipt.receipt_content) {
            $('#receiptContent').text(receipt.receipt_content);
        } else {
            let content = "COUNCIL ERP - PAYMENT RECEIPT\n";
            content += "========================================\n";
            content += `Receipt No: ${receipt.receipt_number}\n`;
            content += `Date: ${new Date().toLocaleString()}\n`;
            content += `Total Amount: $${parseFloat(receipt.total_amount).toFixed(2)}\n`;
            content += "========================================\n";
            content += "Thank you for your payment!";
            $('#receiptContent').text(content);
        }
        
        $('#receiptModal').modal('show');
    }

    function generateQrCode() {
        const billType = $('#qrBillType').val();
        const billId = $('#qrBillId').val();

        if (!billId) {
            showAlert('Please enter a bill ID', 'warning');
            return;
        }

        $.post('/api/finance/pos/generate-qr-code', {
            bill_type: billType,
            bill_id: billId,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(response) {
            if (response.success) {
                $('#qrCodeData').text(response.qr_data);
                $('#qrCodeImage').html(`<img src="${response.qr_url}" alt="QR Code" style="max-width: 200px;">`);
                $('#qrCodeDisplay').show();
            } else {
                showAlert(response.error || 'QR code generation failed', 'error');
            }
        })
        .fail(function() {
            showAlert('QR code generation request failed', 'error');
        });
    }

    function loadTodayStats() {
        const today = new Date().toISOString().split('T')[0];
        $.get('/api/finance/pos/sales-analytics', {
            start_date: today,
            end_date: today
        })
        .done(function(response) {
            if (response.success) {
                $('#todayTransactions').text(response.analytics.total_transactions);
                $('#todayRevenue').text('$' + parseFloat(response.analytics.total_revenue || 0).toFixed(2));
                
                if (response.analytics.total_transactions > 0) {
                    const avg = response.analytics.total_revenue / response.analytics.total_transactions;
                    $('#avgTransaction').text('$' + avg.toFixed(2));
                }
            }
        });
    }

    function updateSessionStats() {
        sessionStats.transactions++;
        sessionStats.revenue += parseFloat($('#totalAmount').val());
        
        const paymentMethod = $('#paymentMethod').val();
        if (paymentMethod === 'cash') sessionStats.cashPayments++;
        if (paymentMethod === 'card') sessionStats.cardPayments++;
        
        $('#sessionTransactions').text(sessionStats.transactions);
        $('#sessionRevenue').text('$' + sessionStats.revenue.toFixed(2));
        $('#cashPayments').text(sessionStats.cashPayments);
        $('#cardPayments').text(sessionStats.cardPayments);
    }

    // Helper functions
    function handleSelectAll() {
        const isChecked = $(this).is(':checked');
        $('.bill-checkbox').prop('checked', isChecked);
        updateSelectedBills();
    }

    function selectAllResults() {
        $('.bill-checkbox').prop('checked', true);
        $('#selectAll').prop('checked', true);
        updateSelectedBills();
    }

    function clearSelectedBills() {
        selectedBills = [];
        $('.bill-checkbox').prop('checked', false);
        $('#selectAll').prop('checked', false);
        $('#selectedBills').hide();
        $('#processPaymentBtn').prop('disabled', true);
        $('#totalAmount').val('0.00');
    }

    function clearSearch() {
        $('#searchValue').val('');
        $('#searchResults').hide();
        $('#customerSuggestions').removeClass('show');
        clearSelectedBills();
    }

    function printReceipt() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head><title>Receipt</title></head>
                <body style="font-family: monospace; padding: 20px;">
                    <pre>${$('#receiptContent').text()}</pre>
                </body>
            </html>
        `);
        printWindow.print();
        printWindow.close();
    }

    function emailReceipt() {
        // Implementation for emailing receipt
        showAlert('Email receipt functionality coming soon!', 'info');
    }

    function getStatusBadge(status) {
        const badges = {
            'sent': '<span class="badge bg-warning">Sent</span>',
            'overdue': '<span class="badge bg-danger">Overdue</span>',
            'partial': '<span class="badge bg-info">Partial</span>',
            'unpaid': '<span class="badge bg-secondary">Unpaid</span>'
        };
        return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
    }

    function getTypeIcon(type) {
        const icons = {
            'invoice': '<i class="fas fa-file-invoice text-primary"></i>',
            'water_bill': '<i class="fas fa-tint text-info"></i>'
        };
        return icons[type] || '<i class="fas fa-file text-secondary"></i>';
    }

    function showAlert(message, type = 'info') {
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';

        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        $('.container-fluid').prepend(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    function showLoading(message) {
        $('body').append(`
            <div id="loadingModal" class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="spinner-border text-primary mb-2" role="status"></div>
                            <p>${message}</p>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }

    function hideLoading() {
        $('#loadingModal').remove();
    }

    // Close suggestions when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#searchValue, #customerSuggestions').length) {
            $('#customerSuggestions').removeClass('show');
        }
    });
});
</script>
@endsection
