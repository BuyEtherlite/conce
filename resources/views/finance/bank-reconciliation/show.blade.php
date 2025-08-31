@extends('layouts.admin')

@section('title', 'Bank Reconciliation Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 Bank Reconciliation Details</h3>
                    <div>
                        @if($reconciliation->status !== 'reconciled')
                        <a href="{{ route('finance.bank-reconciliation.edit', $reconciliation->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('finance.bank-reconciliation.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Reconciliation Number:</th>
                                    <td>{{ $reconciliation->reconciliation_number ?? 'BR-' . str_pad($reconciliation->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <th>Bank Account:</th>
                                    <td>
                                        {{ $reconciliation->bankAccount->account_name ?? 'N/A' }}
                                        <br>
                                        <small class="text-muted">{{ $reconciliation->bankAccount->bank_name ?? '' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reconciliation Date:</th>
                                    <td>{{ $reconciliation->reconciliation_date->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info',
                                                'reconciled' => 'success',
                                                'discrepancy' => 'danger'
                                            ];
                                            $color = $statusColors[$reconciliation->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $color }} badge-lg">
                                            {{ ucfirst(str_replace('_', ' ', $reconciliation->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reconciled By:</th>
                                    <td>{{ $reconciliation->reconciledBy->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Balance Summary</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Bank Statement Balance:</th>
                                            <td class="text-right">
                                                <strong>${{ number_format($reconciliation->statement_balance, 2) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Book Balance:</th>
                                            <td class="text-right">
                                                <strong>${{ number_format($reconciliation->book_balance, 2) }}</strong>
                                            </td>
                                        </tr>
                                        <tr class="border-top">
                                            <th>Difference:</th>
                                            <td class="text-right">
                                                <strong class="text-{{ $reconciliation->difference == 0 ? 'success' : ($reconciliation->difference > 0 ? 'info' : 'danger') }}">
                                                    ${{ number_format($reconciliation->difference, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    @if($reconciliation->difference == 0)
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-check-circle"></i>
                                            Balances are reconciled!
                                        </div>
                                    @elseif($reconciliation->difference > 0)
                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-info-circle"></i>
                                            Bank balance is higher than book balance.
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Book balance is higher than bank balance.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reconciliation->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Notes</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $reconciliation->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Audit Trail</h5>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        <strong>Created:</strong> {{ $reconciliation->created_at->format('F d, Y \a\t g:i A') }}<br>
                                        <strong>Last Updated:</strong> {{ $reconciliation->updated_at->format('F d, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
