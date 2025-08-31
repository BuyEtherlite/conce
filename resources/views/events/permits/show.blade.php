@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Event Permit Details</h1>
                <div>
                    <a href="{{ route('events.permits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    @if($permit->status === 'pending')
                        <a href="{{ route('events.permits.edit', $permit) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Permit Status Alert -->
    <div class="row mb-4">
        <div class="col-12">
            @if($permit->status === 'approved')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> This permit has been approved.
                </div>
            @elseif($permit->status === 'rejected')
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> This permit has been rejected.
                    @if($permit->rejection_reason)
                        <br><strong>Reason:</strong> {{ $permit->rejection_reason }}
                    @endif
                </div>
            @elseif($permit->status === 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i> This permit is pending review.
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Event Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Permit Number:</strong> {{ $permit->permit_number }}</p>
                            <p><strong>Event Name:</strong> {{ $permit->event_name }}</p>
                            <p><strong>Category:</strong> {{ $permit->category->name }}</p>
                            <p><strong>Event Date:</strong> {{ $permit->event_date->format('F j, Y') }}</p>
                            <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($permit->start_time)->format('g:i A') }}</p>
                            <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($permit->end_time)->format('g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Venue:</strong> {{ $permit->venue }}</p>
                            <p><strong>Venue Address:</strong> {{ $permit->venue_address }}</p>
                            <p><strong>Expected Attendance:</strong> {{ number_format($permit->expected_attendance) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p><strong>Event Description:</strong></p>
                        <p class="text-muted">{{ $permit->event_description }}</p>
                    </div>

                    @if($permit->special_requirements)
                        <div class="mt-3">
                            <p><strong>Special Requirements:</strong></p>
                            <p class="text-muted">{{ $permit->special_requirements }}</p>
                        </div>
                    @endif

                    @if($permit->equipment_needed)
                        <div class="mt-3">
                            <p><strong>Equipment Needed:</strong></p>
                            <p class="text-muted">{{ $permit->equipment_needed }}</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        <p><strong>Services:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-{{ $permit->alcohol_service ? 'check text-success' : 'times text-danger' }}"></i> Alcohol Service</li>
                            <li><i class="fas fa-{{ $permit->food_service ? 'check text-success' : 'times text-danger' }}"></i> Food Service</li>
                            <li><i class="fas fa-{{ $permit->amplified_sound ? 'check text-success' : 'times text-danger' }}"></i> Amplified Sound</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Organizer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Organizer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Organizer Name:</strong> {{ $permit->organizer_name }}</p>
                            <p><strong>Email:</strong> {{ $permit->organizer_email }}</p>
                            <p><strong>Phone:</strong> {{ $permit->organizer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Contact Person:</strong> {{ $permit->contact_person }}</p>
                            <p><strong>Contact Email:</strong> {{ $permit->contact_email }}</p>
                            <p><strong>Contact Phone:</strong> {{ $permit->contact_phone }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p><strong>Organizer Address:</strong></p>
                        <p class="text-muted">{{ $permit->organizer_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Fee Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Fee Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Permit Fee:</strong> ${{ number_format($permit->permit_fee, 2) }}</p>
                    <p><strong>Additional Fees:</strong> ${{ number_format($permit->additional_fees, 2) }}</p>
                    <p><strong>Security Deposit:</strong> ${{ number_format($permit->security_deposit, 2) }}</p>
                    <hr>
                    <p><strong>Total Amount:</strong> ${{ number_format($permit->total_amount, 2) }}</p>
                    <p><strong>Payment Status:</strong> 
                        <span class="badge badge-{{ $permit->fee_paid ? 'success' : 'warning' }}">
                            {{ $permit->fee_paid ? 'Paid' : 'Pending' }}
                        </span>
                    </p>
                    @if($permit->payment_due_date)
                        <p><strong>Payment Due:</strong> {{ $permit->payment_due_date->format('F j, Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Timeline</h6>
                </div>
                <div class="card-body">
                    <p><strong>Submitted:</strong> {{ $permit->submitted_at->format('M j, Y g:i A') }}</p>
                    <p><strong>Submitted By:</strong> {{ $permit->createdBy->name ?? 'N/A' }}</p>
                    
                    @if($permit->approved_at)
                        <p><strong>Approved:</strong> {{ $permit->approved_at->format('M j, Y g:i A') }}</p>
                        <p><strong>Approved By:</strong> {{ $permit->approvedBy->name ?? 'N/A' }}</p>
                    @endif
                    
                    @if($permit->rejected_at)
                        <p><strong>Rejected:</strong> {{ $permit->rejected_at->format('M j, Y g:i A') }}</p>
                        <p><strong>Rejected By:</strong> {{ $permit->rejectedBy->name ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(auth()->user()->can('manage_permits') && $permit->status === 'pending')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('events.permits.approve', $permit) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm mb-2" onclick="return confirm('Are you sure you want to approve this permit?')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger btn-sm mb-2" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('events.permits.reject', $permit) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Permit</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required 
                                  placeholder="Please provide a reason for rejecting this permit..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Permit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
