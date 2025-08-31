@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Create General Journal Entry</h1>
        <a href="{{ route('finance.general-journals') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Journals
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.general-journals.store') }}" method="POST" id="journalForm">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Journal Header</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="journal_number">Journal Number</label>
                                    <input type="text" class="form-control" value="{{ $nextJournalNumber }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_date">Transaction Date</label>
                                    <input type="date" class="form-control" name="transaction_date" 
                                           value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select name="currency" class="form-control" required>
                                        <option value="ZWG" {{ old('currency') === 'ZWG' ? 'selected' : '' }}>ZWG</option>
                                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exchange_rate">Exchange Rate</label>
                                    <input type="number" step="0.0001" class="form-control" name="exchange_rate" 
                                           value="{{ old('exchange_rate', '1.0000') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="journal_type">Journal Type</label>
                                    <select name="journal_type" class="form-control" required>
                                        <option value="general" {{ old('journal_type') === 'general' ? 'selected' : '' }}>General</option>
                                        <option value="recurring" {{ old('journal_type') === 'recurring' ? 'selected' : '' }}>Recurring</option>
                                        <option value="reversing" {{ old('journal_type') === 'reversing' ? 'selected' : '' }}>Reversing</option>
                                        <option value="closing" {{ old('journal_type') === 'closing' ? 'selected' : '' }}>Closing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference">Reference</label>
                                    <input type="text" class="form-control" name="reference" 
                                           value="{{ old('reference') }}" maxlength="100">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Journal Lines</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="addLine()">
                            <i class="fas fa-plus"></i> Add Line
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="journalLinesTable">
                                <thead>
                                    <tr>
                                        <th width="30%">Account</th>
                                        <th width="25%">Description</th>
                                        <th width="15%">Debit</th>
                                        <th width="15%">Credit</th>
                                        <th width="10%">Reference</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="journalLines">
                                    <!-- Lines will be added dynamically -->
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="2">Totals</td>
                                        <td class="text-right" id="totalDebit">0.00</td>
                                        <td class="text-right" id="totalCredit">0.00</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Difference</td>
                                        <td colspan="2" class="text-center">
                                            <span id="difference" class="badge badge-secondary">0.00</span>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Save as Draft
                            </button>
                            <button type="button" class="btn btn-info btn-block" onclick="previewJournal()">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <a href="{{ route('finance.general-journals') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Balance Check</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div id="balanceStatus" class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Journal not balanced
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
let lineIndex = 0;
const accounts = @json($accounts);

function addLine() {
    const tbody = document.getElementById('journalLines');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="lines[${lineIndex}][account_id]" class="form-control account-select" required>
                <option value="">Select Account</option>
                ${accounts.map(account => 
                    `<option value="${account.id}">${account.account_code} - ${account.account_name}</option>`
                ).join('')}
            </select>
        </td>
        <td>
            <input type="text" name="lines[${lineIndex}][description]" class="form-control">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][debit_amount]" 
                   class="form-control debit-amount" onchange="updateTotals()">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][credit_amount]" 
                   class="form-control credit-amount" onchange="updateTotals()">
        </td>
        <td>
            <input type="text" name="lines[${lineIndex}][reference]" class="form-control" maxlength="100">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    lineIndex++;
}

function removeLine(button) {
    button.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;

    document.querySelectorAll('.debit-amount').forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });

    document.querySelectorAll('.credit-amount').forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });

    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);

    const difference = Math.abs(totalDebit - totalCredit);
    const differenceElement = document.getElementById('difference');
    const balanceStatus = document.getElementById('balanceStatus');

    differenceElement.textContent = difference.toFixed(2);

    if (difference < 0.01) {
        differenceElement.className = 'badge badge-success';
        balanceStatus.innerHTML = '<i class="fas fa-check-circle"></i> Journal is balanced';
        balanceStatus.className = 'alert alert-success';
    } else {
        differenceElement.className = 'badge badge-danger';
        balanceStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Journal not balanced';
        balanceStatus.className = 'alert alert-warning';
    }
}

// Add initial lines
document.addEventListener('DOMContentLoaded', function() {
    addLine();
    addLine();
});

function previewJournal() {
    // Implementation for journal preview
    alert('Preview functionality to be implemented');
}
</script>
@endpush