@extends('layouts.admin')

@section('title', 'Generate Financial Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📊 Generate Financial Report</h3>
                </div>

                <form action="{{ route('finance.reports.generate') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="report_type">Report Type</label>
                                    <select name="report_type" id="report_type" class="form-control" required>
                                        <option value="">Select Report Type</option>
                                        @foreach($reportTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('report_type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('report_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="report_name">Report Name</label>
                                    <input type="text" name="report_name" id="report_name" class="form-control" 
                                           value="{{ old('report_name') }}" required>
                                    @error('report_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="report_date">Report Date</label>
                                    <input type="date" name="report_date" id="report_date" class="form-control" 
                                           value="{{ old('report_date', date('Y-m-d')) }}" required>
                                    @error('report_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div id="date_range_fields" style="display: none;">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" 
                                               value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" 
                                               value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Report Information</h6>
                                    <p id="report_description">Select a report type to see its description.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> Generate Report
                        </button>
                        <a href="{{ route('finance.reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('report_type');
    const dateRangeFields = document.getElementById('date_range_fields');
    const reportDescription = document.getElementById('report_description');
    
    const descriptions = {
        'balance_sheet': 'Shows financial position at a specific date with assets, liabilities, and equity.',
        'income_statement': 'Shows revenue and expenses over a period of time.',
        'cash_flow': 'Shows cash inflows and outflows over a period of time.',
        'trial_balance': 'Lists all account balances to ensure debits equal credits.',
        'budget_variance': 'Compares budgeted amounts with actual amounts.',
        'ipsas_compliance': 'Generates reports compliant with IPSAS standards.'
    };
    
    const periodReports = ['income_statement', 'cash_flow'];
    
    reportTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        if (periodReports.includes(selectedType)) {
            dateRangeFields.style.display = 'block';
        } else {
            dateRangeFields.style.display = 'none';
        }
        
        if (selectedType && descriptions[selectedType]) {
            reportDescription.textContent = descriptions[selectedType];
        } else {
            reportDescription.textContent = 'Select a report type to see its description.';
        }
    });
});
</script>
@endsection
