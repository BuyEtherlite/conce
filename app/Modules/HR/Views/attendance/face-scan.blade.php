@extends('layouts.app')

@section('title', 'Face Scan Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Face Scan Attendance</h1>
        <a href="{{ route('hr.attendance') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> View All Attendance
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-camera"></i> Face Recognition Attendance
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div id="camera-container" class="mb-4">
                        <video id="video" width="480" height="360" autoplay muted style="border-radius: 8px; border: 2px solid #e3e6f0;"></video>
                        <canvas id="canvas" width="480" height="360" style="display: none;"></canvas>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <button id="check-in-btn" class="btn btn-success btn-lg btn-block" onclick="captureAttendance('check_in')">
                                <i class="fas fa-sign-in-alt"></i> Check In
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button id="check-out-btn" class="btn btn-danger btn-lg btn-block" onclick="captureAttendance('check_out')">
                                <i class="fas fa-sign-out-alt"></i> Check Out
                            </button>
                        </div>
                    </div>

                    <div id="status-message" class="alert" style="display: none;"></div>

                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6>Enrolled Employees</h6>
                            <span class="badge badge-info badge-pill">{{ $enrolledEmployees }}</span>
                        </div>
                        <div class="col-md-4">
                            <h6>Current Time</h6>
                            <span id="current-time" class="badge badge-secondary badge-pill"></span>
                        </div>
                        <div class="col-md-4">
                            <h6>Today's Date</h6>
                            <span class="badge badge-primary badge-pill">{{ date('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title mb-0">Today's Face Scan Attendance</h6>
                </div>
                <div class="card-body">
                    <div id="recent-attendance">
                        <div class="text-center text-muted">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <p>Recent face scan attendance will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i> Attendance Recorded
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-3x text-success"></i>
                </div>
                <h4 id="employee-name"></h4>
                <p class="mb-1"><strong id="action-type"></strong> at <span id="action-time"></span></p>
                <p class="text-muted">Confidence: <span id="confidence-score"></span>%</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let video, canvas, context;
let isProcessing = false;

$(document).ready(function() {
    initializeCamera();
    updateClock();
    setInterval(updateClock, 1000);
    loadRecentAttendance();
});

function initializeCamera() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    context = canvas.getContext('2d');

    navigator.mediaDevices.getUserMedia({ 
        video: { 
            width: 480, 
            height: 360,
            facingMode: 'user'
        } 
    })
    .then(function(stream) {
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        console.error('Error accessing camera:', err);
        showMessage('Unable to access camera. Please check permissions.', 'danger');
    });
}

function captureAttendance(action) {
    if (isProcessing) return;
    
    isProcessing = true;
    const button = action === 'check_in' ? '#check-in-btn' : '#check-out-btn';
    const originalHtml = $(button).html();
    
    $(button).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    $(button).prop('disabled', true);
    
    // Capture image from video
    context.drawImage(video, 0, 0, 480, 360);
    
    // Convert canvas to blob
    canvas.toBlob(function(blob) {
        const formData = new FormData();
        formData.append('face_image', blob, 'face_scan.jpg');
        formData.append('action', action);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '{{ route("hr.attendance.process-face") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showSuccessModal(response);
                    loadRecentAttendance();
                } else {
                    showMessage(response.message || 'Face not recognized', 'danger');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showMessage(response?.message || 'An error occurred', 'danger');
            },
            complete: function() {
                isProcessing = false;
                $(button).html(originalHtml);
                $(button).prop('disabled', false);
            }
        });
    }, 'image/jpeg', 0.8);
}

function showSuccessModal(response) {
    $('#employee-name').text(response.employee);
    $('#action-type').text(response.action.replace('_', ' ').toUpperCase());
    $('#action-time').text(response.time);
    $('#confidence-score').text(Math.round(response.confidence));
    $('#successModal').modal('show');
}

function showMessage(message, type) {
    const alertClass = `alert-${type}`;
    $('#status-message')
        .removeClass()
        .addClass(`alert ${alertClass}`)
        .html(`<i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i> ${message}`)
        .show()
        .delay(5000)
        .fadeOut();
}

function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    $('#current-time').text(timeString);
}

function loadRecentAttendance() {
    $.get('{{ route("hr.attendance.recent-face-scans") }}')
        .done(function(data) {
            if (data.length > 0) {
                let html = '';
                data.forEach(function(item) {
                    const badgeClass = item.action === 'check_in' ? 'success' : 'danger';
                    const icon = item.action === 'check_in' ? 'sign-in-alt' : 'sign-out-alt';
                    
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                            <div>
                                <strong>${item.employee}</strong><br>
                                <small class="text-muted">${item.time}</small>
                            </div>
                            <span class="badge badge-${badgeClass}">
                                <i class="fas fa-${icon}"></i> ${item.action.replace('_', ' ')}
                            </span>
                        </div>
                    `;
                });
                $('#recent-attendance').html(html);
            }
        });
}
</script>
@endpush

@push('styles')
<style>
#video {
    max-width: 100%;
    height: auto;
}

.badge-pill {
    font-size: 0.9em;
}

@media (max-width: 768px) {
    #video {
        width: 100%;
        height: auto;
    }
}
</style>
@endpush
