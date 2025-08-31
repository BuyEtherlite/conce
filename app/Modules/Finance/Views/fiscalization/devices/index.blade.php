@extends('layouts.admin')

@section('title', 'Fiscal Devices')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-desktop me-2"></i>Fiscal Devices
                    </h1>
                    <p class="mb-0 text-muted">Manage ZIMRA-certified fiscal devices</p>
                </div>
                <div>
                    <a href="{{ route('finance.fiscalization.devices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Device
                    </a>
                    <a href="{{ route('finance.fiscalization.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registered Fiscal Devices</h6>
                </div>
                <div class="card-body">
                    @if($devices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Device ID</th>
                                        <th>Device Name</th>
                                        <th>Serial Number</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Assigned User</th>
                                        <th>Status</th>
                                        <th>Total Receipts</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <td>{{ $device->device_id }}</td>
                                            <td>
                                                <div class="font-weight-bold">{{ $device->device_name }}</div>
                                                <small class="text-muted">{{ $device->manufacturer }} {{ $device->model }}</small>
                                            </td>
                                            <td>{{ $device->serial_number }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst(str_replace('_', ' ', $device->device_type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $device->location }}</td>
                                            <td>
                                                @if($device->assignedUser)
                                                    <div>{{ $device->assignedUser->name }}</div>
                                                    <small class="text-muted">{{ $device->assignedUser->email }}</small>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($device->isOperational())
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-{{ 
                                                        $device->status === 'maintenance' ? 'warning' : 
                                                        ($device->status === 'error' ? 'danger' : 'secondary') 
                                                    }}">
                                                        <i class="fas fa-{{ 
                                                            $device->status === 'maintenance' ? 'wrench' : 
                                                            ($device->status === 'error' ? 'exclamation-triangle' : 'pause-circle')
                                                        }} me-1"></i>
                                                        {{ ucfirst($device->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ number_format($device->total_receipts_issued) }}</div>
                                                <small class="text-muted">Last: #{{ str_pad($device->last_receipt_number, 8, '0', STR_PAD_LEFT) }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                            data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="viewDevice({{ $device->id }})">
                                                            <i class="fas fa-eye me-2"></i>View Details
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="editDevice({{ $device->id }})">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                        @if($device->isOperational())
                                                            <a class="dropdown-item text-warning" href="#" onclick="maintenanceMode({{ $device->id }})">
                                                                <i class="fas fa-wrench me-2"></i>Maintenance Mode
                                                            </a>
                                                            <a class="dropdown-item text-danger" href="#" onclick="deactivateDevice({{ $device->id }})">
                                                                <i class="fas fa-power-off me-2"></i>Deactivate
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item text-success" href="#" onclick="activateDevice({{ $device->id }})">
                                                                <i class="fas fa-power-off me-2"></i>Activate
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-desktop fa-4x text-gray-300 mb-4"></i>
                            <h4 class="text-muted">No Fiscal Devices</h4>
                            <p class="text-muted">No fiscal devices have been registered yet.</p>
                            <a href="{{ route('finance.fiscalization.devices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Register First Device
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Device Details Modal -->
<div class="modal fade" id="deviceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Device Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="deviceModalBody">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
function viewDevice(id) {
    // Implementation for viewing device details
    console.log('View device:', id);
}

function editDevice(id) {
    // Implementation for editing device
    console.log('Edit device:', id);
}

function maintenanceMode(id) {
    // Implementation for setting maintenance mode
    console.log('Maintenance mode for device:', id);
}

function activateDevice(id) {
    // Implementation for activating device
    console.log('Activate device:', id);
}

function deactivateDevice(id) {
    // Implementation for deactivating device
    console.log('Deactivate device:', id);
}
</script>
@endsection
