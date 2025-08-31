@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit fa-fw"></i> Custom Reports
        </h1>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
        </a>
    </div>

    <!-- Report Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Report Type</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.custom') }}">
                <div class="row">
                    <div class="col-md-4">
                        <select name="report_type" class="form-control" required>
                            <option value="">Select Report Type</option>
                            <option value="property_valuation" {{ $reportType == 'property_valuation' ? 'selected' : '' }}>Property Valuation Report</option>
                            <option value="license_expiry" {{ $reportType == 'license_expiry' ? 'selected' : '' }}>License Expiry Report</option>
                            <option value="revenue_forecast" {{ $reportType == 'revenue_forecast' ? 'selected' : '' }}>Revenue Forecast</option>
                            <option value="customer_segmentation" {{ $reportType == 'customer_segmentation' ? 'selected' : '' }}>Customer Segmentation</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="months_ahead" class="form-control" placeholder="Months Ahead" value="{{ $parameters['months_ahead'] ?? 3 }}">
                        <small class="form-text text-muted">For License Expiry</small>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="forecast_months" class="form-control" placeholder="Forecast Months" value="{{ $parameters['forecast_months'] ?? 6 }}">
                        <small class="form-text text-muted">For Revenue Forecast</small>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Results -->
    @if($reportType && isset($data))
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ ucfirst(str_replace('_', ' ', $reportType)) }} Results
            </h6>
        </div>
        <div class="card-body">
            @switch($reportType)
                @case('property_valuation')
                    @if($data->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Property ID</th>
                                        <th>Address</th>
                                        <th>Owner</th>
                                        <th>Zone</th>
                                        <th>Current Value</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $property)
                                    <tr>
                                        <td>{{ $property->id }}</td>
                                        <td>{{ $property->address ?? 'N/A' }}</td>
                                        <td>{{ $property->owner->full_name ?? 'N/A' }}</td>
                                        <td>{{ $property->zone->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($property->current_value ?? 0, 2) }}</td>
                                        <td>{{ $property->updated_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No property data found.</p>
                    @endif
                    @break

                @case('license_expiry')
                    @if($data->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>License Number</th>
                                        <th>Business Name</th>
                                        <th>Applicant</th>
                                        <th>License Type</th>
                                        <th>Expiry Date</th>
                                        <th>Days to Expiry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $license)
                                    @php
                                        $daysToExpiry = now()->diffInDays($license->expiry_date, false);
                                    @endphp
                                    <tr class="{{ $daysToExpiry <= 30 ? 'table-danger' : ($daysToExpiry <= 60 ? 'table-warning' : '') }}">
                                        <td>{{ $license->license_number }}</td>
                                        <td>{{ $license->business_name }}</td>
                                        <td>{{ $license->applicant->full_name ?? 'N/A' }}</td>
                                        <td>{{ $license->licenseType->name ?? 'N/A' }}</td>
                                        <td>{{ $license->expiry_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $daysToExpiry <= 30 ? 'danger' : ($daysToExpiry <= 60 ? 'warning' : 'success') }}">
                                                {{ $daysToExpiry }} days
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No licenses expiring in the selected period.</p>
                    @endif
                    @break

                @case('revenue_forecast')
                    @if(isset($data['historical']) && isset($data['forecast']))
                        <div class="row">
                            <div class="col-lg-6">
                                <h6>Historical Revenue (Last 12 Months)</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['historical'] as $month)
                                            <tr>
                                                <td>{{ $month->month }}</td>
                                                <td>${{ number_format($month->revenue, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6>Revenue Forecast</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Projected Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['forecast'] as $forecast)
                                            <tr>
                                                <td>{{ $forecast['month'] }}</td>
                                                <td>${{ number_format($forecast['projected_revenue'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-muted">Unable to generate revenue forecast.</p>
                    @endif
                    @break

                @case('customer_segmentation')
                    @if($data->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Segment</th>
                                        <th>Customer Count</th>
                                        <th>Average Spent</th>
                                        <th>Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $segment)
                                    <tr>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $segment->segment == 'Premium' ? 'success' : 
                                                ($segment->segment == 'Standard' ? 'primary' : 
                                                ($segment->segment == 'Basic' ? 'info' : 'secondary')) 
                                            }}">
                                                {{ $segment->segment }}
                                            </span>
                                        </td>
                                        <td>{{ $segment->customer_count }}</td>
                                        <td>${{ number_format($segment->avg_spent, 2) }}</td>
                                        <td>${{ number_format($segment->total_revenue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No customer segmentation data available.</p>
                    @endif
                    @break

                @default
                    @if(isset($data['message']))
                        <p class="text-center text-muted">{{ $data['message'] }}</p>
                    @endif
            @endswitch
        </div>
    </div>
    @endif
</div>
@endsection
