@extends('layouts.app')

@section('title', 'Immunization Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Immunization Services</h1>
                <p class="page-subtitle">Vaccination programs and immunization tracking</p>
            </div>
        </div>
    </div>

    <!-- Immunization Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Children Vaccinated</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($immunizationData['children_vaccinated']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Adult Vaccinations</div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($immunizationData['adult_vaccinations']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Coverage Rate</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $immunizationData['vaccination_coverage'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Campaigns</div>
                            <div class="h4 mb-0 font-weight-bold">{{ count($immunizationData['upcoming_campaigns']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccination Campaigns -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Campaigns</h5>
                </div>
                <div class="card-body">
                    @foreach($immunizationData['upcoming_campaigns'] as $campaign)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <i class="bi bi-calendar-event text-primary me-3 fa-2x"></i>
                        <div>
                            <h6 class="mb-1">{{ $campaign }}</h6>
                            <small class="text-muted">Registration open</small>
                        </div>
                        <button class="btn btn-sm btn-primary ms-auto">Register</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vaccine Inventory</h5>
                </div>
                <div class="card-body">
                    @foreach($immunizationData['vaccine_inventory'] as $vaccine => $count)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ $vaccine }}</span>
                            <span class="badge bg-{{ $count > 200 ? 'success' : ($count > 100 ? 'warning' : 'danger') }}">{{ $count }} doses</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $count > 200 ? 'success' : ($count > 100 ? 'warning' : 'danger') }}" 
                                 style="width: {{ min(100, ($count / 500) * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Immunization Schedule -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Routine Immunization Schedule</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Age Group</th>
                                    <th>Vaccine</th>
                                    <th>Doses Required</th>
                                    <th>Schedule</th>
                                    <th>Next Available</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Birth - 2 months</td>
                                    <td>BCG, Hepatitis B, Polio</td>
                                    <td>3</td>
                                    <td>At birth, 6 weeks, 10 weeks</td>
                                    <td><span class="badge bg-success">Available Today</span></td>
                                </tr>
                                <tr>
                                    <td>2 - 4 months</td>
                                    <td>DTP, Hib, Pneumococcal</td>
                                    <td>3</td>
                                    <td>6, 10, 14 weeks</td>
                                    <td><span class="badge bg-success">Available Today</span></td>
                                </tr>
                                <tr>
                                    <td>6 - 15 months</td>
                                    <td>Measles, Yellow Fever</td>
                                    <td>2</td>
                                    <td>9, 15 months</td>
                                    <td><span class="badge bg-warning">Next Week</span></td>
                                </tr>
                                <tr>
                                    <td>Adults</td>
                                    <td>COVID-19 Booster</td>
                                    <td>1</td>
                                    <td>Annual</td>
                                    <td><span class="badge bg-success">Available Today</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
