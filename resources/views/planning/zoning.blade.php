@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Zoning & Land Use Planning</h1>
        <div>
            <a href="{{ route('planning.zoning.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Zone
            </a>
            <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Planning
            </a>
        </div>
    </div>

    <!-- Zoning Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Residential Zones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Commercial Zones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Industrial Zones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-industry fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Special Zones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-pin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zoning Map -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Interactive Zoning Map</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-5" style="background-color: #f8f9fc; border: 2px dashed #e3e6f0; border-radius: 5px;">
                        <i class="fas fa-map fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Interactive Zoning Map</h5>
                        <p class="text-muted">Interactive GIS map would be integrated here</p>
                        <button class="btn btn-primary">Load Map</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zoning Classifications -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Zoning Classifications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Zone Code</th>
                            <th>Zone Name</th>
                            <th>Classification</th>
                            <th>Max Building Height</th>
                            <th>Max Coverage</th>
                            <th>Min Setback</th>
                            <th>Permitted Uses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge badge-primary">R1</span></td>
                            <td>Single Residential</td>
                            <td>Residential</td>
                            <td>8m</td>
                            <td>50%</td>
                            <td>4m</td>
                            <td>Single family homes, Home offices</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">R2</span></td>
                            <td>Medium Density Residential</td>
                            <td>Residential</td>
                            <td>12m</td>
                            <td>60%</td>
                            <td>3m</td>
                            <td>Townhouses, Duplexes, Small apartments</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-success">C1</span></td>
                            <td>Local Commercial</td>
                            <td>Commercial</td>
                            <td>15m</td>
                            <td>80%</td>
                            <td>2m</td>
                            <td>Retail shops, Offices, Restaurants</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-warning">I1</span></td>
                            <td>Light Industrial</td>
                            <td>Industrial</td>
                            <td>20m</td>
                            <td>70%</td>
                            <td>5m</td>
                            <td>Warehouses, Light manufacturing</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-info">OS</span></td>
                            <td>Open Space</td>
                            <td>Special</td>
                            <td>5m</td>
                            <td>10%</td>
                            <td>10m</td>
                            <td>Parks, Recreation facilities</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Zoning Tools -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Zoning Tools</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-search me-2"></i> Property Zoning Lookup
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i> Download Zoning Map
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-pdf me-2"></i> Zoning Ordinance (PDF)
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-calculator me-2"></i> Setback Calculator
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Create New Zone
                        </button>
                        <button class="btn btn-info">
                            <i class="fas fa-edit me-2"></i> Amend Zoning
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Report Violation
                        </button>
                        <button class="btn btn-success">
                            <i class="fas fa-check me-2"></i> Approve Variance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: .25rem solid #4e73df!important;
}
.border-left-success {
    border-left: .25rem solid #1cc88a!important;
}
.border-left-warning {
    border-left: .25rem solid #f6c23e!important;
}
.border-left-info {
    border-left: .25rem solid #36b9cc!important;
}
</style>
@endsection
