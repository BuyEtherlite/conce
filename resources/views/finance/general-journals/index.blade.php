@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">General Journals</h1>
        <a href="{{ route('finance.general-journals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Journal Entry
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Journal Entries</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Journal #</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($journals as $journal)
                        <tr>
                            <td>{{ $journal->journal_number }}</td>
                            <td>{{ $journal->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $journal->reference ?? '-' }}</td>
                            <td>{{ Str::limit($journal->description, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($journal->journal_type) }}</span>
                            </td>
                            <td>{{ $journal->currency }}</td>
                            <td class="text-right">{{ number_format($journal->total_debit, 2) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'pending_approval' => 'warning',
                                        'approved' => 'info',
                                        'posted' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$journal->status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $journal->status)) }}
                                </span>
                            </td>
                            <td>{{ $journal->createdBy->name }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('finance.general-journals.show', $journal->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($journal->status === 'draft')
                                    <a href="{{ route('finance.general-journals.edit', $journal->id) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if($journal->status === 'approved')
                                    <form action="{{ route('finance.general-journals.post', $journal->id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i> Post
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
            
            <div class="d-flex justify-content-center">
                {{ $journals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 1, "desc" ]],
        "pageLength": 25
    });
});
</script>
@endpush
