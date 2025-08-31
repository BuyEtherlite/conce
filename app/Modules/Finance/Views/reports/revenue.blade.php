@extends('layouts.admin')

@section('page-title', 'Revenue Analysis Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📈 Revenue Analysis Report</h4>
        <div>
            <button class="btn btn-success" onclick="exportReport('revenue')">
                <i class="fas fa-download me-1"></i>Export
            </button>
            <a href="{{ route('finance.reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Reports
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', now()->startOfYear()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>${{ number_format($revenueData['total_revenue'], 2) }}</h5>
                    <p class="card-text">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>${{ number_format($revenueData['total_revenue'] / max(count($revenueData['monthly_revenue']), 1), 2) }}</h5>
                    <p class="card-text">Average Monthly</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ count($revenueData['revenue_by_category']) }}</h5>
                    <p class="card-text">Revenue Sources</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ $revenueData['period'] }}</h5>
                    <p class="card-text">Report Period</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Revenue Trend -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Monthly Revenue Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Revenue by Category</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Revenue Breakdown -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Revenue Breakdown by Category</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Percentage</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenueData['revenue_by_category'] as $category)
                        <tr>
                            <td>{{ $category['category'] }}</td>
                            <td>${{ number_format($category['amount'], 2) }}</td>
                            <td>{{ number_format(($category['amount'] / $revenueData['total_revenue']) * 100, 1) }}%</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-arrow-up"></i> +2.5%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueData['months']) !!},
        datasets: [{
            label: 'Monthly Revenue',
            data: {!! json_encode($revenueData['monthly_revenue']) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_column($revenueData['revenue_by_category'], 'category')) !!},
        datasets: [{
            data: {!! json_encode(array_column($revenueData['revenue_by_category'], 'amount')) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function exportReport(type) {
    const params = new URLSearchParams({
        start_date: document.getElementById('start_date').value,
        end_date: document.getElementById('end_date').value,
        format: 'csv'
    });
    
    window.open(`/finance/reports/${type}/export?${params}`, '_blank');
}
</script>
@endsection
