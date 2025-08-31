@extends('layouts.app')

@section('title', 'Survey Equipment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Survey Equipment</h4>
                <div class="page-title-right">
                    <a href="{{ route('survey.equipment.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Add Equipment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Equipment List</h4>
                </div>
                <div class="card-body">
                    @if($equipment->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Equipment Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Brand/Model</th>
                                    <th>Status</th>
                                    <th>Next Calibration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment as $item)
                                <tr>
                                    <td><strong>{{ $item->equipment_code }}</strong></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $item->type)) }}</td>
                                    <td>
                                        {{ $item->brand }}
                                        @if($item->model)
                                            <br><small class="text-muted">{{ $item->model }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'available' => 'success',
                                                'in_use' => 'warning',
                                                'maintenance' => 'danger',
                                                'retired' => 'secondary'
                                            ];
                                            $color = $statusColors[$item->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                    </td>
                                    <td>
                                        @if($item->next_calibration_date)
                                            {{ $item->next_calibration_date->format('Y-m-d') }}
                                            @if($item->next_calibration_date->isPast())
                                                <br><span class="badge bg-danger small">Overdue</span>
                                            @elseif($item->next_calibration_date->diffInDays() <= 30)
                                                <br><span class="badge bg-warning small">Due Soon</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewEquipmentModal{{ $item->id }}">
                                                View
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editEquipmentModal{{ $item->id }}">
                                                Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Equipment Modal -->
                                <div class="modal fade" id="viewEquipmentModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Equipment Details: {{ $item->equipment_code }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <dl class="row">
                                                            <dt class="col-sm-5">Name:</dt>
                                                            <dd class="col-sm-7">{{ $item->name }}</dd>
                                                            
                                                            <dt class="col-sm-5">Type:</dt>
                                                            <dd class="col-sm-7">{{ ucwords(str_replace('_', ' ', $item->type)) }}</dd>
                                                            
                                                            <dt class="col-sm-5">Brand:</dt>
                                                            <dd class="col-sm-7">{{ $item->brand ?? 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-5">Model:</dt>
                                                            <dd class="col-sm-7">{{ $item->model ?? 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-5">Serial Number:</dt>
                                                            <dd class="col-sm-7">{{ $item->serial_number ?? 'N/A' }}</dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <dl class="row">
                                                            <dt class="col-sm-6">Purchase Date:</dt>
                                                            <dd class="col-sm-6">{{ $item->purchase_date ? $item->purchase_date->format('Y-m-d') : 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-6">Purchase Cost:</dt>
                                                            <dd class="col-sm-6">${{ $item->purchase_cost ? number_format($item->purchase_cost, 2) : 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-6">Last Calibration:</dt>
                                                            <dd class="col-sm-6">{{ $item->last_calibration_date ? $item->last_calibration_date->format('Y-m-d') : 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-6">Next Calibration:</dt>
                                                            <dd class="col-sm-6">{{ $item->next_calibration_date ? $item->next_calibration_date->format('Y-m-d') : 'N/A' }}</dd>
                                                            
                                                            <dt class="col-sm-6">Status:</dt>
                                                            <dd class="col-sm-6">
                                                                <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                                
                                                @if($item->specifications)
                                                <div class="mt-3">
                                                    <h6>Specifications</h6>
                                                    <p>{{ $item->specifications }}</p>
                                                </div>
                                                @endif
                                                
                                                @if($item->maintenance_notes)
                                                <div class="mt-3">
                                                    <h6>Maintenance Notes</h6>
                                                    <p>{{ $item->maintenance_notes }}</p>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $equipment->links() }}
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-tools display-4 text-muted"></i>
                        <h5 class="mt-3">No Equipment Found</h5>
                        <p class="text-muted">Add your first survey equipment to get started.</p>
                        <a href="{{ route('survey.equipment.create') }}" class="btn btn-primary">Add Equipment</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $equipment->where('status', 'available')->count() }}</h4>
                    <p class="text-muted mb-0">Available</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $equipment->where('status', 'in_use')->count() }}</h4>
                    <p class="text-muted mb-0">In Use</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-danger">{{ $equipment->where('status', 'maintenance')->count() }}</h4>
                    <p class="text-muted mb-0">Maintenance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-secondary">{{ $equipment->where('status', 'retired')->count() }}</h4>
                    <p class="text-muted mb-0">Retired</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
