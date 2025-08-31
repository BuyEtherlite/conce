@extends('layouts.app')

@section('title', 'Emergency Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Emergency Services</h1>
                <p class="page-subtitle">24/7 emergency response and medical services</p>
            </div>
        </div>
    </div>

    <!-- Emergency Status Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Response Time</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $emergencyData['response_time'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Incidents</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $emergencyData['active_incidents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Available Ambulances</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $emergencyData['available_ambulances'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ambulance fa-2x opacity-75"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Hospital Capacity</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $emergencyData['hospitals_capacity'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Emergency Contacts</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($emergencyData['emergency_contacts'] as $service => $contact)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $service }}</h6>
                                    <span class="text-muted">{{ $contact }}</span>
                                </div>
                                <a href="tel:{{ $contact }}" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-telephone"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Emergency Procedures</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Medical Emergency</h6>
                        <ol class="small">
                            <li>Call 911 immediately</li>
                            <li>Stay with the person</li>
                            <li>Follow dispatcher instructions</li>
                            <li>Provide clear location details</li>
                        </ol>
                    </div>
                    <div class="mb-3">
                        <h6>Fire Emergency</h6>
                        <ol class="small">
                            <li>Evacuate immediately</li>
                            <li>Call 911</li>
                            <li>Go to designated safe area</li>
                            <li>Do not re-enter building</li>
                        </ol>
                    </div>
                    <div>
                        <h6>Natural Disaster</h6>
                        <ol class="small">
                            <li>Follow evacuation orders</li>
                            <li>Monitor official channels</li>
                            <li>Have emergency kit ready</li>
                            <li>Stay informed</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
