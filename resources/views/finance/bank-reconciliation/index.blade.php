@extends('layouts.admin')

@section('title', 'Bank Reconciliations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🏦 Bank Reconciliations</h3>
                    <div>
                        <a href="{{ route('finance.bank-reconciliation.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Reconciliation
                        </a>
                        <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Finance
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($reconciliations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Reconciliation #</th>
                                        <th>Bank Account</th>
                                        <th>Date</th>
                                        <th>Statement Balance</th>
                                        <th>Book Balance</th>
                                        <th>Difference</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reconciliations as $reconciliation)
                                    <tr>
                                        <td>
                                            <strong>{{ $reconciliation->reconciliation_number ?? 'BR-' . str_pad($reconciliation->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td>
                                            {{ $reconciliation->bankAccount->account_name ?? 'N/A' }}
                                            <br>
                                            <small class="text-muted">{{ $reconciliation->bankAccount->bank_name ?? '' }}</small>
                                        </td>
                                        <td>{{ $reconciliation->reconciliation_date->format('M d, Y') }}</td>
                                        <td class="text-right">
                                            ${{ number_format($reconciliation->statement_balance, 2) }}
                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($reconciliation->book_balance, 2) }}
                                        </td>
                                        <td class="text-right">
                                            <span class="text-{{ $reconciliation->variance == 0 ? 'success' : ($reconciliation->variance > 0 ? 'info' : 'danger') }}">
                                                ${{ number_format($reconciliation->variance, 2) }}
                                            </span>
                                        </td>
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
                                            <span class="badge badge-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $reconciliation->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('finance.bank-reconciliation.show', $reconciliation->id) }}"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($reconciliation->status !== 'reconciled')
                                                <a href="{{ route('finance.bank-reconciliation.edit', $reconciliation->id) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $reconciliations->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-balance-scale fa-3x text-muted mb-3"></i>
                            <h5>No Bank Reconciliations Found</h5>
                            <p class="text-muted">Start by creating your first bank reconciliation to track differences between bank statements and book balances.</p>
                            <a href="{{ route('finance.bank-reconciliation.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Reconciliation
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Reconciliation Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $reconciliations->where('status', 'pending')->count() }}</h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $reconciliations->where('status', 'in_progress')->count() }}</h4>
                                <p class="mb-0">In Progress</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $reconciliations->where('status', 'reconciled')->count() }}</h4>
                                <p class="mb-0">Reconciled</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $reconciliations->where('status', 'discrepancy')->count() }}</h4>
                                <p class="mb-0">Discrepancies</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection