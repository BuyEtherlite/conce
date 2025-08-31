@extends('layouts.app')

@section('title', 'Cash Position')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>💰 Cash Position</h1>
                <a href="{{ route('finance.cash-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Cash Management
                </a>
            </div>

            <!-- Total Cash Summary -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5>Total Cash Position</h5>
                            <h2>${{ number_format($totalCash, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash by Account -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cash by Account</h5>
                </div>
                <div class="card-body">
                    @if($cashByAccount->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th class="text-end">Balance</th>
                                        <th class="text-end">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashByAccount as $account)
                                        <tr>
                                            <td>{{ $account['name'] }}</td>
                                            <td class="text-end">${{ number_format($account['balance'], 2) }}</td>
                                            <td class="text-end">{{ number_format($account['percentage'], 1) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No bank accounts found.</p>
                            <a href="{{ route('finance.cash-management.bank-accounts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Bank Account
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
