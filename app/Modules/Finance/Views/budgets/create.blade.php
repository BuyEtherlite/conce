@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">💰 Create Budget</h3>
                </div>

                <form action="{{ route('finance.budgets.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget_name">Budget Name *</label>
                                    <input type="text" class="form-control @error('budget_name') is-invalid @enderror" 
                                           id="budget_name" name="budget_name" value="{{ old('budget_name') }}" required>
                                    @error('budget_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">Account *</label>
                                    <select class="form-control @error('account_id') is-invalid @enderror" 
                                            id="account_id" name="account_id" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_code }} - {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_center_id">Cost Center</label>
                                    <select class="form-control @error('cost_center_id') is-invalid @enderror" 
                                            id="cost_center_id" name="cost_center_id">
                                        <option value="">Select Cost Center</option>
                                        @foreach($costCenters as $costCenter)
                                            <option value="{{ $costCenter->id }}" {{ old('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                                {{ $costCenter->cost_center_code }} - {{ $costCenter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cost_center_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budgeted_amount">Budgeted Amount *</label>
                                    <input type="number" step="0.01" class="form-control @error('budgeted_amount') is-invalid @enderror" 
                                           id="budgeted_amount" name="budgeted_amount" value="{{ old('budgeted_amount') }}" required>
                                    @error('budgeted_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="budget_period">Budget Period *</label>
                                    <select class="form-control @error('budget_period') is-invalid @enderror" 
                                            id="budget_period" name="budget_period" required>
                                        <option value="">Select Period</option>
                                        <option value="monthly" {{ old('budget_period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('budget_period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="annually" {{ old('budget_period') == 'annually' ? 'selected' : '' }}>Annually</option>
                                    </select>
                                    @error('budget_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Budget</button>
                        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
