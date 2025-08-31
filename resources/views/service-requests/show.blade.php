@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt fa-fw"></i> Service Request: {{ $serviceRequest->request_number }}
        </h1>
        <div class="d-none d-lg-inline-block">
            <a href="{{ route('service-requests.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Requests
            </a>
            <a href="{{ route('service-requests.edit', $serviceRequest) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Request
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
                                @if($serviceRequest->is_emergency)
                                    - EMERGENCY
                                @endif
                            </span><br>
                            <strong>Status:</strong>
                            <span class="badge badge-{{ 
                                $serviceRequest->status == 'completed' ? 'success' :
                                ($serviceRequest->status == 'in_progress' ? 'primary' :
                                ($serviceRequest->status == 'assigned' ? 'info' :
                                ($serviceRequest->status == 'on_hold' ? 'secondary' :
                                ($serviceRequest->status == 'cancelled' ? 'danger' : 'warning'))))
                            }}">
                                {{ ucwords(str_replace('_', ' ', $serviceRequest->status)) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer:</strong> {{ $serviceRequest->customer->full_name ?? 'N/A' }}<br>
                            <strong>Contact Phone:</strong> {{ $serviceRequest->contact_phone ?? $serviceRequest->customer->phone ?? 'N/A' }}<br>
                            <strong>Contact Email:</strong> {{ $serviceRequest->contact_email ?? $serviceRequest->customer->email ?? 'N/A' }}<br>
                            <strong>Department:</strong> {{ $serviceRequest->department->name ?? 'N/A' }}<br>
                            <strong>Assigned Team:</strong> {{ $serviceRequest->assignedTeam->name ?? 'Unassigned' }}
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Description:</strong><br>
                            <p class="mt-2">{{ $serviceRequest->description }}</p>
                        </div>
                    </div>

                    @if($serviceRequest->location_address)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Location:</strong><br>
                            <p class="mt-2">{{ $serviceRequest->location_address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($serviceRequest->resolution_notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Resolution Notes:</strong><br>
                            <p class="mt-2">{{ $serviceRequest->resolution_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status History -->
            @if($serviceRequest->statusHistory->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($serviceRequest->statusHistory->sortByDesc('created_at') as $history)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge badge-{{ 
                                        $history->new_status == 'completed' ? 'success' :
                                        ($history->new_status == 'in_progress' ? 'primary' :
                                        ($history->new_status == 'assigned' ? 'info' : 'warning'))
                                    }}">
                                        {{ ucwords(str_replace('_', ' ', $history->new_status)) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $history->changedBy->name ?? 'System' }}</div>
                                    <div class="text-muted small">{{ $history->created_at->format('M d, Y H:i') }}</div>
                                    @if($history->notes)
                                        <div class="mt-1">{{ $history->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Updates -->
            @if($serviceRequest->updates->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Updates</h6>
                </div>
                <div class="card-body">
                    @foreach($serviceRequest->updates->sortByDesc('created_at') as $update)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $update->createdBy->name ?? 'System' }}</strong>
                            <small class="text-muted">{{ $update->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <div class="mt-2">{{ $update->message }}</div>
                        @if($update->update_type)
                            <span class="badge badge-info mt-1">{{ ucwords(str_replace('_', ' ', $update->update_type)) }}</span>
                        @endif
                    </div>
                    @endforeach
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
                            <div>
                                <i class="fas fa-file"></i>
                                <span class="ms-2">{{ $attachment->file_name }}</span>
                                <small class="text-muted">({{ number_format($attachment->file_size / 1024, 2) }} KB)</small>
                            </div>
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->status !== 'completed' && $serviceRequest->status !== 'cancelled')
                        <!-- Update Status Form -->
                        <form action="{{ route('service-requests.updateStatus', $serviceRequest) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="submitted" {{ $serviceRequest->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="acknowledged" {{ $serviceRequest->status == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                    <option value="assigned" {{ $serviceRequest->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="in_progress" {{ $serviceRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="on_hold" {{ $serviceRequest->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ $serviceRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $serviceRequest->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any relevant notes..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                        </form>

                        @if(!$serviceRequest->assigned_team_id)
                        <!-- Assignment Form -->
                        <form action="{{ route('service-requests.assign', $serviceRequest) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="assigned_team_id" class="form-label">Assign to Team</label>
                                <select name="assigned_team_id" id="assigned_team_id" class="form-control" required>
                                    <option value="">Select Team</option>
                                    @foreach(\App\Models\ServiceTeam::where('is_active', true)->get() as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignment_notes" class="form-label">Assignment Notes</label>
                                <textarea name="notes" id="assignment_notes" class="form-control" rows="2" placeholder="Assignment instructions..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Assign Request</button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Request Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Created:</strong><br>
                        <span class="text-muted">{{ $serviceRequest->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    
                    @if($serviceRequest->expected_completion_date)
                    <div class="mb-2">
                        <strong>Expected Completion:</strong><br>
                        <span class="text-muted">{{ $serviceRequest->expected_completion_date->format('M d, Y H:i') }}</span>
                    </div>
                    @endif

                    @if($serviceRequest->actual_completion_date)
                    <div class="mb-2">
                        <strong>Completed:</strong><br>
                        <span class="text-muted">{{ $serviceRequest->actual_completion_date->format('M d, Y H:i') }}</span>
                    </div>
                    @endif

                    @if($serviceRequest->estimated_cost)
                    <div class="mb-2">
                        <strong>Estimated Cost:</strong><br>
                        <span class="text-muted">${{ number_format($serviceRequest->estimated_cost, 2) }}</span>
                    </div>
                    @endif

                    @if($serviceRequest->actual_cost)
                    <div class="mb-2">
                        <strong>Actual Cost:</strong><br>
                        <span class="text-muted">${{ number_format($serviceRequest->actual_cost, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
