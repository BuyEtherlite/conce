@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt fa-fw"></i> Service Request: {{ $serviceRequest->request_number }}
        </h1>
        <div class="d-none d-lg-inline-block">
            <a href="{{ route('public-services.requests') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Requests
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Request Number:</strong> {{ $serviceRequest->request_number }}<br>
                            <strong>Title:</strong> {{ $serviceRequest->title }}<br>
                            <strong>Service Type:</strong> {{ $serviceRequest->serviceType->name ?? 'N/A' }}<br>
                            <strong>Priority:</strong> 
                            <span class="badge badge-{{ 
                                $serviceRequest->priority == 'low' ? 'secondary' : 
                                ($serviceRequest->priority == 'medium' ? 'info' : 
                                ($serviceRequest->priority == 'high' ? 'warning' : 
                                ($serviceRequest->priority == 'urgent' ? 'orange' : 'danger'))) 
                            }}">
                                {{ ucfirst($serviceRequest->priority) }}
                            </span><br>
                            <strong>Status:</strong>
                            <span class="badge badge-{{ 
                                $serviceRequest->status == 'completed' ? 'success' : 
                                ($serviceRequest->status == 'in_progress' ? 'primary' : 
                                ($serviceRequest->status == 'submitted' ? 'warning' : 'secondary')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer:</strong> {{ $serviceRequest->customer->full_name ?? 'N/A' }}<br>
                            <strong>Contact Phone:</strong> {{ $serviceRequest->contact_phone ?? 'N/A' }}<br>
                            <strong>Contact Email:</strong> {{ $serviceRequest->contact_email ?? 'N/A' }}<br>
                            <strong>Requested Date:</strong> {{ $serviceRequest->requested_date->format('M d, Y H:i') }}<br>
                            <strong>Expected Completion:</strong> {{ $serviceRequest->expected_completion_date ? $serviceRequest->expected_completion_date->format('M d, Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <strong>Description:</strong><br>
                            <p>{{ $serviceRequest->description }}</p>
                        </div>
                    </div>

                    @if($serviceRequest->location_address)
                    <div class="row">
                        <div class="col-12">
                            <strong>Location:</strong> {{ $serviceRequest->location_address }}
                            @if($serviceRequest->ward_number)
                                (Ward: {{ $serviceRequest->ward_number }})
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Updates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Updates</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->updates->count() > 0)
                        @foreach($serviceRequest->updates as $update)
                        <div class="d-flex mb-3">
                            <div class="mr-3">
                                <div class="icon-circle bg-{{ $update->update_type == 'success' ? 'success' : ($update->update_type == 'warning' ? 'warning' : ($update->update_type == 'error' ? 'danger' : 'info')) }}">
                                    <i class="fas fa-{{ $update->update_type == 'success' ? 'check' : ($update->update_type == 'warning' ? 'exclamation-triangle' : ($update->update_type == 'error' ? 'times' : 'info-circle')) }} text-white"></i>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="small text-gray-500">{{ $update->created_at->format('M d, Y H:i') }} by {{ $update->createdBy->name ?? 'System' }}</div>
                                <div>{{ $update->message }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No updates yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('public-services.requests.update-status', $serviceRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="submitted" {{ $serviceRequest->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="acknowledged" {{ $serviceRequest->status == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                <option value="assigned" {{ $serviceRequest->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $serviceRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="on_hold" {{ $serviceRequest->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="completed" {{ $serviceRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="closed" {{ $serviceRequest->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="cancelled" {{ $serviceRequest->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Assignment -->
            @if($serviceRequest->assignedUser || $serviceRequest->assignedTeam)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assignment</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->assignedUser)
                        <strong>Assigned to:</strong> {{ $serviceRequest->assignedUser->name }}<br>
                    @endif
                    @if($serviceRequest->assignedTeam)
                        <strong>Team:</strong> {{ $serviceRequest->assignedTeam->name }}<br>
                    @endif
                    @if($serviceRequest->department)
                        <strong>Department:</strong> {{ $serviceRequest->department->name }}
                    @endif
                </div>
            </div>
            @endif

            <!-- Attachments -->
            @if($serviceRequest->attachments->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attachments</h6>
                </div>
                <div class="card-body">
                    @foreach($serviceRequest->attachments as $attachment)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $attachment->file_name }}</span>
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
