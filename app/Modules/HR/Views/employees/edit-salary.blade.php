@extends('layouts.app')

@section('title', 'Edit Employee Salary')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Salary - {{ $employee->full_name }}</h1>
        <a href="{{ route('hr.employees.show', $employee) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Employee
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave"></i> Multi-Currency Salary Configuration
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.employees.update-salary', $employee) }}" method="POST" id="salary-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="basic_salary">Basic Salary <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('basic_salary') is-invalid @enderror" 
                                               id="basic_salary" 
                                               name="basic_salary" 
                                               value="{{ old('basic_salary', $employee->basic_salary) }}" 
                                               step="0.01" 
                                               min="0" 
                                               required>
                                        <div class="input-group-append">
                                            <select name="basic_salary_currency" class="form-control" id="basic_currency">
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency }}" 
                                                            {{ old('basic_salary_currency', $employee->basic_salary_currency) == $currency ? 'selected' : '' }}>
                                                        {{ $currency }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('basic_salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allowances">Allowances</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('allowances') is-invalid @enderror" 
                                               id="allowances" 
                                               name="allowances" 
                                               value="{{ old('allowances', $employee->allowances) }}" 
                                               step="0.01" 
                                               min="0">
                                        <div class="input-group-append">
                                            <select name="allowances_currency" class="form-control" id="allowances_currency">
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency }}" 
                                                            {{ old('allowances_currency', $employee->allowances_currency) == $currency ? 'selected' : '' }}>
                                                        {{ $currency }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('allowances')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Salary Breakdown -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-pie"></i> Salary Breakdown (Optional)
                                    <button type="button" class="btn btn-sm btn-outline-primary float-right" id="add-component">
                                        <i class="fas fa-plus"></i> Add Component
                                    </button>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="salary-components">
                                    @if($employee->salary_breakdown)
                                        @foreach($employee->salary_breakdown as $index => $component)
                                            <div class="row salary-component mb-3">
                                                <div class="col-md-3">
                                                    <input type="text" 
                                                           name="salary_breakdown[{{ $index }}][component]" 
                                                           class="form-control" 
                                                           placeholder="Component name"
                                                           value="{{ $component['component'] }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" 
                                                           name="salary_breakdown[{{ $index }}][amount]" 
                                                           class="form-control" 
                                                           placeholder="Amount"
                                                           value="{{ $component['amount'] }}"
                                                           step="0.01" 
                                                           min="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="salary_breakdown[{{ $index }}][currency]" class="form-control">
                                                        @foreach($currencies as $currency)
                                                            <option value="{{ $currency }}" 
                                                                    {{ ($component['currency'] ?? 'USD') == $currency ? 'selected' : '' }}>
                                                                {{ $currency }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" 
                                                           name="salary_breakdown[{{ $index }}][percentage]" 
                                                           class="form-control" 
                                                           placeholder="%" 
                                                           value="{{ $component['percentage'] ?? '' }}"
                                                           min="0" 
                                                           max="100">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-component">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <small class="text-muted">
                                    Break down salary into different components with different currencies if needed.
                                </small>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('effective_date') is-invalid @enderror" 
                                           id="effective_date" 
                                           name="effective_date" 
                                           value="{{ old('effective_date', date('Y-m-d')) }}" 
                                           required>
                                    @error('effective_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reason">Reason for Change <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                                              id="reason" 
                                              name="reason" 
                                              rows="3" 
                                              placeholder="Explain the reason for salary adjustment"
                                              required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Salary
                                </button>
                                <a href="{{ route('hr.employees.show', $employee) }}" class="btn btn-secondary ml-2">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Salary History -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history"></i> Salary History
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($salaryHistory as $adjustment)
                        <div class="timeline-item mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $adjustment->effective_date->format('M d, Y') }}</h6>
                                    <p class="mb-1">
                                        <strong>Basic:</strong> 
                                        {{ number_format($adjustment->new_basic_salary, 2) }} {{ $adjustment->new_basic_currency }}
                                        @if($adjustment->new_allowances > 0)
                                            <br><strong>Allowances:</strong> 
                                            {{ number_format($adjustment->new_allowances, 2) }} {{ $adjustment->new_allowances_currency }}
                                        @endif
                                    </p>
                                    <small class="text-muted">{{ $adjustment->reason }}</small>
                                </div>
                                <div class="text-right">
                                    @if($adjustment->new_basic_salary > $adjustment->old_basic_salary)
                                        <i class="fas fa-arrow-up text-success"></i>
                                    @elseif($adjustment->new_basic_salary < $adjustment->old_basic_salary)
                                        <i class="fas fa-arrow-down text-danger"></i>
                                    @else
                                        <i class="fas fa-equals text-info"></i>
                                    @endif
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-3">
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <p>No salary history available</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Current Salary Summary -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calculator"></i> Current Salary Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6>Basic Salary</h6>
                            <h4 class="text-primary">
                                {{ number_format($employee->basic_salary, 2) }}
                                <small>{{ $employee->basic_salary_currency }}</small>
                            </h4>
                        </div>
                        <div class="col-6">
                            <h6>Allowances</h6>
                            <h4 class="text-info">
                                {{ number_format($employee->allowances, 2) }}
                                <small>{{ $employee->allowances_currency }}</small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let componentIndex = {{ count($employee->salary_breakdown ?? []) }};

$(document).ready(function() {
    $('#add-component').click(function() {
        addSalaryComponent();
    });

    $(document).on('click', '.remove-component', function() {
        $(this).closest('.salary-component').remove();
    });
});

function addSalaryComponent() {
    const currencies = @json($currencies);
    let currencyOptions = '';
    currencies.forEach(currency => {
        currencyOptions += `<option value="${currency}">${currency}</option>`;
    });

    const componentHtml = `
        <div class="row salary-component mb-3">
            <div class="col-md-3">
                <input type="text" 
                       name="salary_breakdown[${componentIndex}][component]" 
                       class="form-control" 
                       placeholder="Component name">
            </div>
            <div class="col-md-3">
                <input type="number" 
                       name="salary_breakdown[${componentIndex}][amount]" 
                       class="form-control" 
                       placeholder="Amount"
                       step="0.01" 
                       min="0">
            </div>
            <div class="col-md-2">
                <select name="salary_breakdown[${componentIndex}][currency]" class="form-control">
                    ${currencyOptions}
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" 
                       name="salary_breakdown[${componentIndex}][percentage]" 
                       class="form-control" 
                       placeholder="%" 
                       min="0" 
                       max="100">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-component">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    $('#salary-components').append(componentHtml);
    componentIndex++;
}
</script>
@endpush
