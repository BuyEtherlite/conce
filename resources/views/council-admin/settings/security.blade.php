@extends('layouts.council-admin')

@section('title', 'Security Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Security Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.settings.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active">Security</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5><i class="fas fa-shield-alt me-2"></i>Security Configuration</h5>
                    <button class="btn btn-success btn-sm" onclick="saveSecuritySettings()">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Authentication Settings -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-key me-2"></i>Authentication</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enforceStrongPasswords" checked>
                                        <label class="form-check-label" for="enforceStrongPasswords">
                                            Enforce Strong Passwords
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableTwoFactor">
                                        <label class="form-check-label" for="enableTwoFactor">
                                            Enable Two-Factor Authentication
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="requirePasswordExpiry" checked>
                                        <label class="form-check-label" for="requirePasswordExpiry">
                                            Password Expiry (90 days)
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="maxLoginAttempts" class="form-label">Max Login Attempts</label>
                                        <select class="form-select" id="maxLoginAttempts">
                                            <option value="3">3 attempts</option>
                                            <option value="5" selected>5 attempts</option>
                                            <option value="10">10 attempts</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                                        <select class="form-select" id="sessionTimeout">
                                            <option value="30">30 minutes</option>
                                            <option value="60" selected>1 hour</option>
                                            <option value="120">2 hours</option>
                                            <option value="480">8 hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Access Control -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-users-cog me-2"></i>Access Control</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableRBAC" checked>
                                        <label class="form-check-label" for="enableRBAC">
                                            Role-Based Access Control
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableIPWhitelist">
                                        <label class="form-check-label" for="enableIPWhitelist">
                                            IP Address Whitelist
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableAuditLogging" checked>
                                        <label class="form-check-label" for="enableAuditLogging">
                                            Comprehensive Audit Logging
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableConcurrentSessions">
                                        <label class="form-check-label" for="enableConcurrentSessions">
                                            Allow Multiple Concurrent Sessions
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="adminAccessLevel" class="form-label">Admin Access Restrictions</label>
                                        <select class="form-select" id="adminAccessLevel">
                                            <option value="unrestricted">Unrestricted</option>
                                            <option value="office_hours" selected>Office Hours Only</option>
                                            <option value="specific_ips">Specific IPs Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Protection -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-database me-2"></i>Data Protection</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableDataEncryption" checked>
                                        <label class="form-check-label" for="enableDataEncryption">
                                            Enable Data Encryption at Rest
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableDataBackup" checked>
                                        <label class="form-check-label" for="enableDataBackup">
                                            Automated Daily Backups
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableDataMasking">
                                        <label class="form-check-label" for="enableDataMasking">
                                            Data Masking for Non-Admin Users
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dataRetention" class="form-label">Data Retention Period</label>
                                        <select class="form-select" id="dataRetention">
                                            <option value="1">1 year</option>
                                            <option value="3">3 years</option>
                                            <option value="5" selected>5 years</option>
                                            <option value="7">7 years</option>
                                            <option value="indefinite">Indefinite</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Monitoring -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-chart-line me-2"></i>System Monitoring</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableIntrusionDetection" checked>
                                        <label class="form-check-label" for="enableIntrusionDetection">
                                            Intrusion Detection System
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableFailedLoginAlert" checked>
                                        <label class="form-check-label" for="enableFailedLoginAlert">
                                            Failed Login Alerts
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="enableUnusualActivityAlert">
                                        <label class="form-check-label" for="enableUnusualActivityAlert">
                                            Unusual Activity Detection
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="logRetention" class="form-label">Security Log Retention</label>
                                        <select class="form-select" id="logRetention">
                                            <option value="30">30 days</option>
                                            <option value="90" selected>90 days</option>
                                            <option value="180">6 months</option>
                                            <option value="365">1 year</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Status Dashboard -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shield-check me-2"></i>Security Status Dashboard</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h6>System Secure</h6>
                                    <p class="small mb-0">All security measures active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h6>Active Sessions</h6>
                                    <h4 class="mb-0">12</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <h6>Failed Logins (24h)</h6>
                                    <h4 class="mb-0">3</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-2x mb-2"></i>
                                    <h6>Last Backup</h6>
                                    <p class="small mb-0">2 hours ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveSecuritySettings() {
    const settings = {
        authentication: {
            enforce_strong_passwords: document.getElementById('enforceStrongPasswords').checked,
            enable_two_factor: document.getElementById('enableTwoFactor').checked,
            require_password_expiry: document.getElementById('requirePasswordExpiry').checked,
            max_login_attempts: document.getElementById('maxLoginAttempts').value,
            session_timeout: document.getElementById('sessionTimeout').value
        },
        access_control: {
            enable_rbac: document.getElementById('enableRBAC').checked,
            enable_ip_whitelist: document.getElementById('enableIPWhitelist').checked,
            enable_audit_logging: document.getElementById('enableAuditLogging').checked,
            enable_concurrent_sessions: document.getElementById('enableConcurrentSessions').checked,
            admin_access_level: document.getElementById('adminAccessLevel').value
        },
        data_protection: {
            enable_data_encryption: document.getElementById('enableDataEncryption').checked,
            enable_data_backup: document.getElementById('enableDataBackup').checked,
            enable_data_masking: document.getElementById('enableDataMasking').checked,
            data_retention: document.getElementById('dataRetention').value
        },
        monitoring: {
            enable_intrusion_detection: document.getElementById('enableIntrusionDetection').checked,
            enable_failed_login_alert: document.getElementById('enableFailedLoginAlert').checked,
            enable_unusual_activity_alert: document.getElementById('enableUnusualActivityAlert').checked,
            log_retention: document.getElementById('logRetention').value
        }
    };

    fetch('/council-admin/settings/security/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Security settings saved successfully!');
        } else {
            alert('Error saving settings: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving security settings');
    });
}
</script>
@endsection