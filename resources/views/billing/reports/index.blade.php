@extends('layouts.admin')

@section('page-title', 'Billing Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Billing Reports & Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                                    <h6>Revenue Report</h6>
                                    <p class="text-muted">View revenue trends and analytics</p>
                                    <button class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h6>Outstanding Bills</h6>
                                    <p class="text-muted">View overdue and pending payments</p>
                                    <button class="btn btn-warning">View Outstanding</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                                    <h6>Customer Report</h6>
                                    <p class="text-muted">Customer billing summary</p>
                                    <button class="btn btn-success">Customer Summary</button>
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
