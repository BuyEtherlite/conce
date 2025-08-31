@extends('layouts.admin')

@section('title', 'Create Journal Entry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📝 Create Journal Entry</h1>
                <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to General Ledger
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('finance.general-ledger.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="transaction_date">Transaction Date *</label>
                                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}">
                                    @error('transaction_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="reference_number">Reference Number</label>
                                    <input type="text" class="form-control" id="reference_number" name="reference_number" value="{{ old('reference_number') }}">
                                    @error('reference_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5>Journal Entries</h5>
                        <div id="journal-entries">
                            <div class="journal-entry mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Account *</label>
                                        <select class="form-control" name="entries[0][account_code]" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->account_code }}" {{ old('entries.0.account_code') == $account->account_code ? 'selected' : '' }}>
                                                    {{ $account->account_code }} - {{ $account->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Debit Amount</label>
                                        <input type="number" class="form-control debit-amount" name="entries[0][debit_amount]" step="0.01" min="0" value="{{ old('entries.0.debit_amount') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Credit Amount</label>
                                        <input type="number" class="form-control credit-amount" name="entries[0][credit_amount]" step="0.01" min="0" value="{{ old('entries.0.credit_amount') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-entry" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="journal-entry mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Account *</label>
                                        <select class="form-control" name="entries[1][account_code]" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->account_code }}" {{ old('entries.1.account_code') == $account->account_code ? 'selected' : '' }}>
                                                    {{ $account->account_code }} - {{ $account->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Debit Amount</label>
                                        <input type="number" class="form-control debit-amount" name="entries[1][debit_amount]" step="0.01" min="0" value="{{ old('entries.1.debit_amount') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Credit Amount</label>
                                        <input type="number" class="form-control credit-amount" name="entries[1][credit_amount]" step="0.01" min="0" value="{{ old('entries.1.credit_amount') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-entry" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-info mb-3" id="add-entry">
                            <i class="fas fa-plus"></i> Add Entry
                        </button>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Journal Totals</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Total Debits:</span>
                                            <strong>$<span id="total-debits">0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Total Credits:</span>
                                            <strong>$<span id="total-credits">0.00</span></strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span><strong>Difference:</strong></span>
                                            <strong id="difference">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Journal Entry
                            </button>
                            <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-secondary">
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
let entryCount = 2;

document.getElementById('add-entry').addEventListener('click', function() {
    const entriesContainer = document.getElementById('journal-entries');
    const newEntry = document.createElement('div');
    newEntry.className = 'journal-entry mb-3';
    newEntry.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label>Account *</label>
                <select class="form-control" name="entries[${entryCount}][account_code]" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->account_code }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Debit Amount</label>
                <input type="number" class="form-control debit-amount" name="entries[${entryCount}][debit_amount]" step="0.01" min="0">
            </div>
            <div class="col-md-3">
                <label>Credit Amount</label>
                <input type="number" class="form-control credit-amount" name="entries[${entryCount}][credit_amount]" step="0.01" min="0">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-entry">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    entriesContainer.appendChild(newEntry);
    entryCount++;
    updateRemoveButtons();
    attachEntryListeners();
});

function updateRemoveButtons() {
    const entries = document.querySelectorAll('.journal-entry');
    const removeButtons = document.querySelectorAll('.remove-entry');

    removeButtons.forEach((button, index) => {
        button.style.display = entries.length > 2 ? 'block' : 'none';
        button.onclick = function() {
            entries[index].remove();
            updateRemoveButtons();
            calculateTotals();
        };
    });
}

function attachEntryListeners() {
    const debitInputs = document.querySelectorAll('.debit-amount');
    const creditInputs = document.querySelectorAll('.credit-amount');

    debitInputs.forEach(input => {
        input.removeEventListener('input', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });

    creditInputs.forEach(input => {
        input.removeEventListener('input', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });
}

function calculateTotals() {
    let totalDebits = 0;
    let totalCredits = 0;

    document.querySelectorAll('.debit-amount').forEach(input => {
        totalDebits += parseFloat(input.value) || 0;
    });

    document.querySelectorAll('.credit-amount').forEach(input => {
        totalCredits += parseFloat(input.value) || 0;
    });

    const difference = totalDebits - totalCredits;

    document.getElementById('total-debits').textContent = totalDebits.toFixed(2);
    document.getElementById('total-credits').textContent = totalCredits.toFixed(2);

    const differenceElement = document.getElementById('difference');
    differenceElement.textContent = '$' + Math.abs(difference).toFixed(2);

    if (difference === 0) {
        differenceElement.className = 'text-success';
    } else {
        differenceElement.className = 'text-danger';
    }
}

// Initialize
updateRemoveButtons();
attachEntryListeners();
calculateTotals();
</script>
@endsection