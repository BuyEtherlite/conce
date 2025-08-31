@extends('layouts.app')

@section('title', 'Health Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Health Services</h1>
                <p class="page-subtitle">Public health services and programs available to the community</p>
            </div>
        </div>
    </div>

    <!-- Services Overview Cards -->
    <div class="row">
        @foreach($services as $service)
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $service['name'] }}</h5>
                    <p class="card-text">{{ $service['description'] }}</p>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $service['availability'] }}
                        </small><br>
                        <small class="text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $service['location'] }}
                        </small>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm">Learn More</button>
                    <button class="btn btn-outline-secondary btn-sm">Schedule Appointment</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Access -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Access</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('health.emergency.index') }}" class="btn btn-danger w-100">
                                <i class="bi bi-exclamation-triangle"></i> Emergency Services
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('health.immunization.index') }}" class="btn btn-success w-100">
                                <i class="bi bi-shield-check"></i> Immunization
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('health.maternal-health.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-heart"></i> Maternal Health
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('health.permits.index') }}" class="btn btn-warning w-100">
                                <i class="bi bi-clipboard-check"></i> Health Permits
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contact Health Services</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Main Health Office</h6>
                            <p>
                                <i class="bi bi-telephone"></i> (555) 123-4567<br>
                                <i class="bi bi-envelope"></i> health@municipality.gov<br>
                                <i class="bi bi-clock"></i> Mon-Fri: 8:00 AM - 5:00 PM
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6>Emergency Line</h6>
                            <p>
                                <i class="bi bi-telephone-fill text-danger"></i> 911<br>
                                <i class="bi bi-clock"></i> 24/7 Available
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6>Health Inspector</h6>
                            <p>
                                <i class="bi bi-telephone"></i> (555) 123-4568<br>
                                <i class="bi bi-envelope"></i> inspector@municipality.gov<br>
                                <i class="bi bi-clock"></i> Mon-Fri: 9:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
