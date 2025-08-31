@extends('layouts.install')

@section('title', 'Install Council ERP System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card install-card">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">🚀 Council ERP Installation</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">❌ Please fix the following issues:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- System Requirements Check -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="border-bottom pb-2">🔍 System Requirements</h4>
                        <p class="text-muted">Please ensure your system meets the following requirements:</p>

                        @if(!collect($requirements)->every('status'))
                            <div class="alert alert-info">
                                <h6 class="alert-heading">💡 First time deploying to hosting?</h6>
                                <p class="mb-2">If you just uploaded files to your hosting provider and see failed requirements, this is normal!</p>
                                <p class="mb-0">
                                    <strong>Most common fix:</strong> Run <code>composer install --no-dev</code> in your hosting terminal.
                                    <a href="#deployment-help" data-bs-toggle="collapse" class="ms-2">View deployment guide</a>
                                </p>
                            </div>

                            <div class="collapse" id="deployment-help">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>🚀 Quick Deployment Fix for NameCheap</h6>
                                        <ol class="mb-2">
                                            <li>Access your cPanel File Manager</li>
                                            <li>Navigate to your public_html/game.rhinotap.net directory</li>
                                            <li>Open Terminal in cPanel</li>
                                            <li>Run: <code>cd public_html/game.rhinotap.net</code></li>
                                            <li>Run: <code>composer install --no-dev</code></li>
                                            <li>Set permissions: <code>chmod -R 775 storage/ bootstrap/cache/</code></li>
                                            <li>Refresh this page</li>
                                        </ol>
                                        <p class="small mb-0">
                                            <strong>Need help?</strong> Contact NameCheap support if you can't access terminal.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h6>PHP Requirements</h6>
                        <div class="requirements-list">
                            @foreach($requirements as $req)
                                <div class="requirement-item d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $req['name'] }}</span>
                                    <span class="badge {{ $req['status'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $req['status'] ? '✓' : '✗' }} {{ $req['current'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>Folder Permissions</h6>
                        <div class="permissions-list">
                            @foreach($permissions as $perm)
                                <div class="permission-item d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $perm['name'] }}</span>
                                    <span class="badge {{ $perm['status'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $perm['status'] ? '✓ Writable' : '✗ Not Writable' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @php
                    $allRequirementsMet = collect($requirements)->every('status') && collect($permissions)->every('status');
                @endphp

                @if(!$allRequirementsMet)
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">⚠️ System Requirements Not Met</h6>
                        <p class="mb-0">Please resolve the above issues before proceeding with the installation.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('install.store') }}" id="installForm" {{ !$allRequirementsMet ? 'style=display:none' : '' }}>
                    @csrf

                    <!-- Site Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="border-bottom pb-2">📝 Site Settings</h4>
                        </div>
                        <div class="col-md-6">
                            <label for="site_name" class="form-label">Site Name *</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                   value="{{ old('site_name', 'City Council ERP') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="site_description" class="form-label">Site Description</label>
                            <input type="text" class="form-control" id="site_description" name="site_description" 
                                   value="{{ old('site_description', 'Municipal ERP Management System') }}">
                        </div>
                    </div>

                    <!-- Database Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="border-bottom pb-2">🗄️ Database Settings</h4>
                            <p class="text-muted">Enter your NameCheap MySQL database connection details:</p>
                        </div>
                        <div class="col-md-6">
                            <label for="db_host" class="form-label">Database Host *</label>
                            <input type="text" class="form-control" id="db_host" name="db_host" 
                                   value="{{ old('db_host', 'localhost') }}" required>
                            <small class="text-muted">Usually 'localhost' for NameCheap</small>
                        </div>
                        <div class="col-md-6">
                            <label for="db_port" class="form-label">Database Port *</label>
                            <input type="number" class="form-control" id="db_port" name="db_port" 
                                   value="{{ old('db_port', '3306') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="db_database" class="form-label">Database Name *</label>
                            <input type="text" class="form-control" id="db_database" name="db_database" 
                                   value="{{ old('db_database', 'council_erp') }}" required>
                            <small class="text-muted">Create this database in your cPanel first</small>
                        </div>
                        <div class="col-md-6">
                            <label for="db_username" class="form-label">Database Username *</label>
                            <input type="text" class="form-control" id="db_username" name="db_username" 
                                   value="{{ old('db_username') }}" required>
                        </div>
                        <div class="col-md-8">
                            <label for="db_password" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_password" name="db_password">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary w-100" id="testDbConnection">
                                <span id="testDbText">🔍 Test Connection</span>
                                <span id="testDbSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </div>
                        <div class="col-12 mt-2">
                            <div id="dbTestResult" style="display: none;"></div>
                        </div>
                    </div>

                    <!-- Admin User Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="border-bottom pb-2">👤 Admin User Settings</h4>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_name" class="form-label">Admin Name *</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" 
                                   value="{{ old('admin_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_email" class="form-label">Admin Email *</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                   value="{{ old('admin_email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_password" class="form-label">Admin Password *</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="admin_password_confirmation" 
                                   name="admin_password_confirmation" required>
                        </div>
                    </div>

                    <!-- Council Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="border-bottom pb-2">🏛️ Council Details</h4>
                        </div>
                        <div class="col-md-12">
                            <label for="council_name" class="form-label">Council Name *</label>
                            <input type="text" class="form-control" id="council_name" name="council_name" 
                                   value="{{ old('council_name') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label for="council_address" class="form-label">Council Address *</label>
                            <textarea class="form-control" id="council_address" name="council_address" rows="3" required>{{ old('council_address') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="council_contact" class="form-label">Contact Information *</label>
                            <textarea class="form-control" id="council_contact" name="council_contact" rows="2" 
                                      placeholder="Phone, Email, Website" required>{{ old('council_contact') }}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="installButton" disabled>
                            <span id="installText">🚀 Install Council ERP System</span>
                            <span id="installSpinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                        </button>
                        <p class="text-muted mt-2 small">
                            <i class="fas fa-info-circle"></i> Please test database connection before installing
                        </p>
                    </div>
                </form>

                @if(!$allRequirementsMet)
                    <div class="text-center">
                        <button class="btn btn-outline-secondary btn-lg px-5" disabled>
                            ⚠️ Resolve System Requirements First
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const testDbBtn = document.getElementById('testDbConnection');
    const installButton = document.getElementById('installButton');
    const installForm = document.getElementById('installForm');
    let dbConnectionTested = false;

    // Test database connection
    testDbBtn.addEventListener('click', function() {
        const dbHost = document.getElementById('db_host').value;
        const dbPort = document.getElementById('db_port').value;
        const dbDatabase = document.getElementById('db_database').value;
        const dbUsername = document.getElementById('db_username').value;
        const dbPassword = document.getElementById('db_password').value;

        if (!dbHost || !dbPort || !dbDatabase || !dbUsername) {
            showDbResult('Please fill in all required database fields.', 'danger');
            return;
        }

        // Show loading state
        document.getElementById('testDbText').textContent = 'Testing...';
        document.getElementById('testDbSpinner').style.display = 'inline-block';
        testDbBtn.disabled = true;

        // Make AJAX request
        fetch('{{ route("install.test-database") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                db_host: dbHost,
                db_port: dbPort,
                db_database: dbDatabase,
                db_username: dbUsername,
                db_password: dbPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDbResult(data.message, 'success');
                dbConnectionTested = true;
                installButton.disabled = false;
                document.getElementById('installText').textContent = '🚀 Install Council ERP System';
            } else {
                showDbResult(data.message, 'danger');
                dbConnectionTested = false;
                installButton.disabled = true;
            }
        })
        .catch(error => {
            showDbResult('An error occurred while testing the database connection.', 'danger');
            dbConnectionTested = false;
            installButton.disabled = true;
        })
        .finally(() => {
            // Reset button state
            document.getElementById('testDbText').textContent = '🔍 Test Connection';
            document.getElementById('testDbSpinner').style.display = 'none';
            testDbBtn.disabled = false;
        });
    });

    // Handle form submission
    installForm.addEventListener('submit', function(e) {
        if (!dbConnectionTested) {
            e.preventDefault();
            showDbResult('Please test the database connection first.', 'warning');
            return;
        }

        // Show loading state during form submission
        installButton.disabled = true;
        document.getElementById('installText').textContent = 'Installing...';
        document.getElementById('installSpinner').style.display = 'inline-block';
    });

    // Reset database test status when fields change
    const dbFields = ['db_host', 'db_port', 'db_database', 'db_username', 'db_password'];
    dbFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                dbConnectionTested = false;
                installButton.disabled = true;
                hideDbResult();
            });
        }
    });

    function showDbResult(message, type) {
        const resultDiv = document.getElementById('dbTestResult');
        resultDiv.className = `alert alert-${type}`;
        resultDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'times-circle' : 'exclamation-triangle'}"></i> ${message}`;
        resultDiv.style.display = 'block';
    }

    function hideDbResult() {
        document.getElementById('dbTestResult').style.display = 'none';
    }
});
</script>
@endsection
@extends('layouts.install')

@section('title', 'Welcome - Council ERP Installation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card install-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h1 class="display-4 text-primary">🏛️ Council ERP</h1>
                    <p class="lead">Municipal Management System</p>
                </div>

                <div class="welcome-content">
                    <h3 class="text-center mb-4">Welcome to the Installation Wizard</h3>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">💰</div>
                            <h5>Finance Management</h5>
                            <p>Complete financial accounting, budgeting, and revenue management</p>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">👥</div>
                            <h5>Human Resources</h5>
                            <p>Employee management, payroll, and attendance tracking</p>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">🏠</div>
                            <h5>Housing Management</h5>
                            <p>Property management, tenant allocation, and waiting lists</p>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">📋</div>
                            <h5>Service Requests</h5>
                            <p>Citizen service management and request tracking</p>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">🏪</div>
                            <h5>Market Management</h5>
                            <p>Market stall allocation and revenue collection</p>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">📊</div>
                            <h5>Reporting & Analytics</h5>
                            <p>Comprehensive reporting and business intelligence</p>
                        </div>
                    </div>

                    <div class="installation-note">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">📋 Before You Begin</h6>
                            <ul class="mb-0">
                                <li>Ensure you have a MySQL database ready</li>
                                <li>Have your database credentials available</li>
                                <li>Make sure your server meets the system requirements</li>
                                <li>The installation process may take a few minutes</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('install.step1') }}" class="btn btn-primary btn-lg px-5">
                            🚀 Start Installation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.install-card {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 15px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.feature-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.feature-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.installation-note {
    margin: 30px 0;
}

.welcome-content {
    max-width: 800px;
    margin: 0 auto;
}
</style>
@endsection
