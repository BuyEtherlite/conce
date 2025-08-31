@extends('layouts.admin')

@section('title', 'Transfer Funds')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Transfer Funds</h3>
                    <a href="{{ route('finance.bank-manager.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Bank Manager
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('finance.bank-manager.store-transfer') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_account_id">From Account</label>
                                    <select class="form-control" id="from_account_id" name="from_account_id" required>
                                        <option value="">Select Source Account</option>
                                        @foreach($bankAccounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->account_name }} - ${{ number_format($account->current_balance, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_account_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_account_id">To Account</label>
                                    <select class="form-control" id="to_account_id" name="to_account_id" required>
                                        <option value="">Select Destination Account</option>
                                        @foreach($bankAccounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->account_name }} - ${{ number_format($account->current_balance, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_account_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" 
                                           name="amount" step="0.01" min="0.01" required>
                                    @error('amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transfer_date">Transfer Date</label>
                                    <input type="date" class="form-control" id="transfer_date" 
                                           name="transfer_date" value="{{ date('Y-m-d') }}" required>
                                    @error('transfer_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" 
                                   name="description" placeholder="Enter transfer description" required>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reference_number">Reference Number (Optional)</label>
                            <input type="text" class="form-control" id="reference_number" 
                                   name="reference_number" placeholder="Enter reference number">
                            @error('reference_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-exchange-alt"></i> Transfer Funds
                            </button>
                            <a href="{{ route('finance.bank-manager.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
