@extends('layouts.admin')

@section('page-title', 'General Ledger')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📚 General Ledger</h4>
        <div>
            <a href="{{ route('finance.general-ledger.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Journal Entry
            </a>
            <a href="{{ route('finance.reports.trial-balance') }}" class="btn btn-outline-secondary">
                <i class="fas fa-balance-scale me-1"></i>Trial Balance
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Account</label>
                    <select name="account_code" class="form-select">
                        <option value="">All Accounts</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->account_code }}" {{ request('account_code') == $account->account_code ? 'selected' : '' }}>
                                {{ $account->full_account_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                        <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>Reversed</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- General Ledger Entries -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">General Ledger Entries</h6>
        </div>
        <div class="card-body">
            @if($entries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Reference</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                                <tr>
                                    <td>{{ $entry->transaction_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('finance.general-ledger.show', $entry) }}" class="text-decoration-none">
                                            {{ $entry->transaction_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $entry->account_code }}</small><br>
                                        {{ $entry->account->account_name ?? 'Unknown Account' }}
                                    </td>
                                    <td>{{ Str::limit($entry->description, 50) }}</td>
                                    <td>{{ $entry->reference_number }}</td>
                                    <td class="text-end">
                                        @if($entry->debit_amount > 0)
                                            <span class="text-success fw-bold">
                                                ${{ number_format($entry->debit_amount, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($entry->credit_amount > 0)
                                            <span class="text-danger fw-bold">
                                                ${{ number_format($entry->credit_amount, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($entry->program_code)
                                            <small class="badge bg-info">{{ $entry->program_code }}</small>
                                        @endif
                                        @if($entry->project_code)
                                            <br><small class="badge bg-secondary">{{ $entry->project_code }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($entry->status === 'posted')
                                            <span class="badge bg-success">Posted</span>
                                        @elseif($entry->status === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @else
                                            <span class="badge bg-danger">Reversed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('finance.general-ledger.show', $entry) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($entry->status === 'draft')
                                                <form method="POST" action="{{ route('finance.general-ledger.approve', $entry) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success" title="Approve & Post" 
                                                            onclick="return confirm('Are you sure you want to approve and post this entry?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }} entries
                    </div>
                    <div>
                        {{ $entries->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No general ledger entries found</h5>
                    <p class="text-muted">Create your first journal entry to get started.</p>
                    <a href="{{ route('finance.general-ledger.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Journal Entry
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when date inputs change
    $('input[name="from_date"], input[name="to_date"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush