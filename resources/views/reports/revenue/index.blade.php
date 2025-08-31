@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line fa-fw"></i> Revenue Analysis
        </h1>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Date Range Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.revenue') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" placeholder="End Date">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.revenue') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Revenue Trend -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Trend</h6>
                </div>
                <div class="card-body">
                    @if($monthlyRevenue->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyRevenue as $month)
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $month->month, 1)) }}</td>
                                        <td>{{ $month->year }}</td>
                                        <td>${{ number_format($month->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="2">Total</td>
                                        <td>${{ number_format($monthlyRevenue->sum('total'), 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No revenue data found for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Revenue by Department -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue by Department</h6>
                </div>
                <div class="card-body">
                    @if($departmentRevenue->count() > 0)
                        @foreach($departmentRevenue as $dept)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ $dept->department_name }}</span>
                                <span class="font-weight-bold">${{ number_format($dept->total, 2) }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" 
                                     style="width: {{ ($dept->total / $departmentRevenue->max('total')) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">No department revenue data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Revenue Sources -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Revenue Sources (Customers)</h6>
                </div>
                <div class="card-body">
                    @if($topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCustomers as $index => $customer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $customer->customer_name }}</td>
                                        <td>${{ number_format($customer->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No customer revenue data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
