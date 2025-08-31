@extends('layouts.app')

@section('page-title', 'Tax Rates Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📊 Tax Rates Management</h4>
        <div>
            <a href="{{ route('finance.tax-management.rates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Tax Rate
            </a>
            <a href="{{ route('finance.tax-management.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6><i class="fas fa-percentage me-2"></i>Tax Rates</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Rate</th>
                            <th>Type</th>
                            <th>Effective Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxRates as $rate)
                        <tr>
                            <td>
                                {{ $rate->name }}
                                @if($rate->is_default)
                                    <span class="badge bg-primary ms-1">Default</span>
                                @endif
                            </td>
                            <td>{{ $rate->code }}</td>
                            <td>
                                <strong>{{ $rate->rate }}{{ $rate->type === 'percentage' ? '%' : '' }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $rate->type === 'percentage' ? 'info' : 'warning' }}">
                                    {{ ucfirst($rate->type) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $rate->effective_from ? \Carbon\Carbon::parse($rate->effective_from)->format('M d, Y') : 'N/A' }}
                                    @if($rate->effective_to)
                                        - {{ \Carbon\Carbon::parse($rate->effective_to)->format('M d, Y') }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $rate->is_active ? 'success' : 'danger' }}">
                                    {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('finance.tax-management.rates.edit', $rate) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$rate->is_default)
                                    <form action="{{ route('finance.tax-management.rates.destroy', $rate) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this tax rate?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No tax rates found. <a href="{{ route('finance.tax-management.rates.create') }}">Create the first one</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($taxRates->hasPages())
            <div class="d-flex justify-content-center">
                {{ $taxRates->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
