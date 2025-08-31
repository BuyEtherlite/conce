@extends('layouts.install')

@section('title', 'Installation Complete - Council ERP')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card install-card">
            <div class="card-body p-5 text-center">
                <div class="step-indicator">
                    <div class="step completed">1</div>
                    <div class="step completed">2</div>
                    <div class="step completed">3</div>
                </div>

                <div class="mb-4">
                    <div class="display-1 text-success">✅</div>
                    <h2 class="text-success">Installation Complete!</h2>
                    <p class="lead text-muted">Your Council ERP System has been successfully installed and configured.</p>
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title">📋 Admin Login Credentials</h5>
                        <div class="alert alert-info">
                            <strong>⚠️ IMPORTANT:</strong> Please copy these credentials to a secure location before proceeding.
                        </div>
                        <div class="row text-start">
                            <div class="col-sm-4"><strong>Name:</strong></div>
                            <div class="col-sm-8">
                                <code>{{ $admin->name }}</code>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $admin->name }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-sm-4"><strong>Email/Username:</strong></div>
                            <div class="col-sm-8">
                                <code>{{ $admin->email }}</code>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $admin->email }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-sm-4"><strong>Password:</strong></div>
                            <div class="col-sm-8">
                                <code id="adminPassword">{{ request()->session()->get('temp_admin_password', 'Use the password you entered during installation') }}</code>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard(document.getElementById('adminPassword').textContent)">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-sm-4"><strong>Role:</strong></div>
                            <div class="col-sm-8"><span class="badge bg-primary">Administrator</span></div>
                            <div class="col-sm-4"><strong>Created:</strong></div>
                            <div class="col-sm-8">{{ $admin->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <h6 class="alert-heading">🔐 Security Notice</h6>
                    <p class="mb-0">Please save these admin credentials in a secure location. You can use these credentials to log in and create additional users, assign them to departments and offices, and configure the system modules.</p>
                </div>

                <div class="alert alert-warning">
                    <h6 class="alert-heading">⚠️ Next Steps</h6>
                    <ul class="text-start mb-0">
                        <li>Log in with your admin credentials</li>
                        <li>Create departments and offices</li>
                        <li>Set up additional users and assign roles</li>
                        <li>Configure module permissions for each department</li>
                        <li>Start using the ERP modules</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <a href="/login" class="btn btn-primary btn-lg me-3">
                        🔑 Continue to Login
                    </a>
                    <a href="/dashboard" class="btn btn-outline-primary btn-lg">
                        🏠 Go to Dashboard
                    </a>
                </div>

                <div class="mt-4 text-muted small">
                    <p>🎉 Welcome to your new Council ERP System!</p>
                    <p>Installation completed at {{ now()->format('M d, Y H:i:s') }}</p>
                    <p><strong>Next Steps:</strong> Use the "Continue to Login" button to access your system with the credentials above.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Create a temporary success message
        const btn = event.target.closest('button');
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => {
            btn.innerHTML = originalIcon;
        }, 2000);
    }, function(err) {
        console.error('Could not copy text: ', err);
        // Fallback for older browsers
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            const btn = event.target.closest('button');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 2000);
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }
        document.body.removeChild(textArea);
    });
}
</script>
@endsection
@extends('layouts.install')

@section('title', 'Installation Complete - Council ERP')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card install-card">
            <div class="card-body p-5 text-center">
                <div class="success-animation mb-4">
                    <div class="checkmark">
                        <svg class="checkmark__circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle__background" cx="26" cy="26" r="25" fill="none"/>
                            <circle class="checkmark__circle__check" cx="26" cy="26" r="25" fill="none"/>
                        </svg>
                        <svg class="checkmark__check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <path class="checkmark__check__path" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-success mb-3">🎉 Installation Complete!</h1>
                <p class="lead mb-4">Council ERP has been successfully installed and configured.</p>

                <div class="installation-summary">
                    <div class="alert alert-success">
                        <h6 class="alert-heading">✅ What has been installed:</h6>
                        <ul class="list-unstyled mb-0">
                            <li>✓ Database tables created</li>
                            <li>✓ System configuration applied</li>
                            <li>✓ Default data seeded</li>
                            <li>✓ Application key generated</li>
                            <li>✓ Cache cleared</li>
                        </ul>
                    </div>
                </div>

                <div class="next-steps mb-4">
                    <h5>🚀 Next Steps:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="step-card">
                                <h6>1. Access the System</h6>
                                <p>Start using your Council ERP system immediately</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-card">
                                <h6>2. Configure Departments</h6>
                                <p>Set up your municipal departments and offices</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-5 me-3">
                        🏛️ Access Council ERP
                    </a>
                    <a href="{{ url('/admin') }}" class="btn btn-outline-secondary">
                        ⚙️ Admin Panel
                    </a>
                </div>

                <div class="security-note mt-4">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">🔒 Important Security Note:</h6>
                        <p class="mb-0">For security reasons, the installation files will be automatically disabled. If you need to reinstall, please delete the <code>storage/app/installed.lock</code> file.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkmark {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

.checkmark__circle {
    width: 100px;
    height: 100px;
}

.checkmark__circle__background {
    stroke: #e9ecef;
    stroke-width: 2;
}

.checkmark__circle__check {
    stroke: #28a745;
    stroke-width: 2;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: stroke 2s cubic-bezier(0.650, 0.000, 0.450, 1.000) forwards;
}

.checkmark__check {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 0;
    left: 0;
}

.checkmark__check__path {
    stroke: #28a745;
    stroke-width: 3;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 1s cubic-bezier(0.650, 0.000, 0.450, 1.000) 1s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

.step-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
    border: 1px solid #e9ecef;
}

.success-animation {
    position: relative;
}
</style>
@endsection
