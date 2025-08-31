@extends('layouts.admin')

@section('title', 'Cash Transaction Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">💰 Transaction Details</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.cashbook.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Cashbook
                        </a>
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Transaction Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Transaction Type:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->transaction_type === 'receipt' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td class="text-{{ $transaction->transaction_type === 'receipt' ? 'success' : 'danger' }}">
                                        <strong>${{ number_format($transaction->amount, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account:</strong></td>
                                    <td>{{ $transaction->account->account_name ?? 'N/A' }}</td>
                                </tr>
                                @if($transaction->bankAccount)
                                <tr>
                                    <td><strong>Bank Account:</strong></td>
                                    <td>{{ $transaction->bankAccount->account_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Additional Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $transaction->description }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reconciled:</strong></td>
                                    <td>
                                        @if($transaction->reconciled_at)
                                            <span class="badge badge-success">Yes</span>
                                            <small class="text-muted d-block">{{ $transaction->reconciled_at->format('d M Y') }}</small>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->transaction_type === 'receipt')
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-plus-circle"></i>
                        This is a cash receipt transaction that increased the cash balance.
                    </div>
                    @else
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-minus-circle"></i>
                        This is a cash payment transaction that decreased the cash balance.
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Transaction ID: {{ $transaction->id }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                Last updated: {{ $transaction->updated_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
