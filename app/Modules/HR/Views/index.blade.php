@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">HR Management Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">HR Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Total Employees</h5>
                            <h3 class="text-primary">{{ $stats['total_employees'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-users font-size-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Active Employees</h5>
                            <h3 class="text-success">{{ $stats['active_employees'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i class="fas fa-user-check font-size-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Departments</h5>
                            <h3 class="text-info">{{ $stats['departments'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i class="fas fa-building font-size-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Pending Leave Requests</h5>
                            <h3 class="text-warning">{{ $stats['pending_leave_requests'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="fas fa-calendar-alt font-size-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">HR Management Modules</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="{{ route('hr.employees') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-users d-block mb-2" style="font-size: 2rem;"></i>
                                    Employee Management
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="{{ route('hr.departments') }}" class="btn btn-outline-info btn-lg w-100">
                                    <i class="fas fa-building d-block mb-2" style="font-size: 2rem;"></i>
                                    Departments
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="{{ route('hr.attendance') }}" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-clock d-block mb-2" style="font-size: 2rem;"></i>
                                    Attendance
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="{{ route('hr.leave-requests') }}" class="btn btn-outline-warning btn-lg w-100">
                                    <i class="fas fa-calendar-check d-block mb-2" style="font-size: 2rem;"></i>
                                    Leave Management
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="{{ route('hr.payroll') }}" class="btn btn-outline-danger btn-lg w-100">
                                    <i class="fas fa-money-bill-wave d-block mb-2" style="font-size: 2rem;"></i>
                                    Payroll
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="#" class="btn btn-outline-secondary btn-lg w-100">
                                    <i class="fas fa-chart-line d-block mb-2" style="font-size: 2rem;"></i>
                                    Performance Reviews
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="#" class="btn btn-outline-dark btn-lg w-100">
                                    <i class="fas fa-graduation-cap d-block mb-2" style="font-size: 2rem;"></i>
                                    Training
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <a href="#" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-file-contract d-block mb-2" style="font-size: 2rem;"></i>
                                    Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Pending Actions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Employees</h4>
                </div>
                <div class="card-body">
                    @if($recentEmployees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Hire Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEmployees as $employee)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        {{ substr($employee->full_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                                    <small class="text-muted">{{ $employee->employee_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $employee->department->name }}</td>
                                        <td>{{ $employee->position->title }}</td>
                                        <td>{{ $employee->hire_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent employees found</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pending Leave Requests</h4>
                </div>
                <div class="card-body">
                    @if($pendingLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Leave Type</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingLeaves as $leave)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $leave->employee->full_name }}</h6>
                                                <small class="text-muted">{{ $leave->employee->department->name }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $leave->leaveType->name }}</td>
                                        <td>
                                            {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}
                                            <br><small class="text-muted">{{ $leave->days_requested }} days</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success btn-sm" onclick="approveLeave({{ $leave->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="rejectLeave({{ $leave->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No pending leave requests</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveLeave(id) {
    if (confirm('Are you sure you want to approve this leave request?')) {
        fetch(`/hr/leave-requests/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error approving leave request');
            }
        });
    }
}

function rejectLeave(id) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason) {
        fetch(`/hr/leave-requests/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                approval_notes: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rejecting leave request');
            }
        });
    }
}
</script>
@endsection
@extends('layouts.app')

@section('title', 'HR Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">HR Management Dashboard</h1>
    </div>

    <!-- Summary Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Employees
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_employees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Employees
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['active_employees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Present Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['present_today'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Face Enrolled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['face_enrolled'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus"></i> Add Employee
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.face-enrollment') }}" class="btn btn-info btn-block">
                                <i class="fas fa-camera"></i> Face Enrollment
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.attendance.face-scan') }}" class="btn btn-success btn-block">
                                <i class="fas fa-qrcode"></i> Face Attendance
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.payroll') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill-wave"></i> Process Payroll
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">New Employee Added</h6>
                                <p class="timeline-text">John Doe joined Marketing Department</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Face Enrollment Completed</h6>
                                <p class="timeline-text">5 employees enrolled their faces</p>
                                <small class="text-muted">4 hours ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Salary Updated</h6>
                                <p class="timeline-text">Multi-currency salary adjustments processed</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    height: 100%;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    margin-bottom: 5px;
}
</style>
@endsection
