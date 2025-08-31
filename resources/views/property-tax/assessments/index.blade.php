@extends('layouts.app')

@section('title', 'Property Tax Assessments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Property Tax Assessments</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('property-tax.index') }}">Property Tax</a></li>
                        <li class="breadcrumb-item active">Assessments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Property Tax Assessments</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('property-tax.assessments.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i>New Assessment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Property ID</th>
                                    <th>Category</th>
                                    <th>Zone</th>
                                    <th>Assessed Value</th>
                                    <th>Tax Rate</th>
                                    <th>Annual Tax</th>
                                    <th>Assessment Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assessments as $assessment)
                                <tr>
                                    <td>{{ $assessment->property_id }}</td>
                                    <td>{{ $assessment->category->name ?? 'N/A' }}</td>
                                    <td>{{ $assessment->zone->zone_name ?? 'N/A' }}</td>
                                    <td>${{ number_format($assessment->assessed_value, 2) }}</td>
                                    <td>{{ $assessment->tax_rate }}%</td>
                                    <td>${{ number_format($assessment->annual_tax, 2) }}</td>
                                    <td>{{ $assessment->assessment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $assessment->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($assessment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('property-tax.bills.generate', $assessment->id) }}" 
                                               class="btn btn-sm btn-outline-primary">Generate Bill</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-4 d-block mb-2"></i>
                                            No property tax assessments found
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($assessments->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $assessments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
