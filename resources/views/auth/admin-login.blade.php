@extends('layouts.app')

@section('title', 'Council Admin Login - Council ERP')

@section('content')
<div class="login-container">
    <div class="login-background">
        <div class="geometric-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>
    
    <div class="login-content">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h1 class="brand-title">Council Admin</h1>
                    <p class="brand-subtitle">Administrative Portal</p>
                </div>
            </div>

            <div class="login-form-container">
                <h2 class="form-title">Administrator Access</h2>
                <p class="form-subtitle">Secure admin login portal</p>

                <form method="POST" action="{{ route('admin.login.submit') }}" class="login-form">
                    @csrf

                    <div class="input-group">
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" 
                                   class="form-input @error('email') error @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="Admin Email Address">
                            <label for="email" class="floating-label">Admin Email Address</label>
                        </div>
                        @error('email')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" type="password" 
                                   class="form-input @error('password') error @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Admin Password">
                            <label for="password" class="floating-label">Admin Password</label>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span class="checkbox-text">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="login-button">
                        <span class="button-text">Admin Sign In</span>
                        <i class="fas fa-shield-alt button-icon"></i>
                    </button>
                </form>

                <div class="staff-login-notice">
                    <p class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Department staff should use the 
                            <a href="{{ route('login') }}" class="staff-link">Regular Staff Login</a>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    overflow-x: hidden;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    min-height: 100vh;
}

.login-container {
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
}

.login-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(220, 38, 38, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(239, 68, 68, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(185, 28, 28, 0.3) 0%, transparent 50%);
    animation: backgroundPulse 10s ease-in-out infinite;
}

@keyframes backgroundPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.geometric-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    animation: float 15s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 5s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 10s;
}

.shape-4 {
    width: 120px;
    height: 120px;
    top: 30%;
    right: 40%;
    animation-delay: 7s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    33% {
        transform: translateY(-30px) rotate(120deg);
    }
    66% {
        transform: translateY(20px) rotate(240deg);
    }
}

.login-content {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 450px;
    padding: 20px;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 35px 60px -12px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.3);
}

.login-header {
    padding: 40px 40px 20px;
    text-align: center;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    position: relative;
}

.login-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.logo-container {
    position: relative;
    z-index: 2;
}

.logo-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.logo-icon i {
    font-size: 30px;
    color: white;
}

.brand-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px;
    letter-spacing: -0.5px;
}

.brand-subtitle {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.login-form-container {
    padding: 40px;
}

.form-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px;
    text-align: center;
}

.form-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 32px;
    text-align: center;
}

.input-group {
    margin-bottom: 24px;
}

.input-wrapper {
    position: relative;
}

.form-input {
    width: 100%;
    padding: 16px 16px 16px 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #fafafa;
    color: #1a1a1a;
}

.form-input:focus {
    outline: none;
    border-color: #dc2626;
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.25);
}

.form-input.error {
    border-color: #ef4444;
    background: #fef2f2;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
    transition: color 0.3s ease;
}

.form-input:focus + .floating-label,
.form-input:focus ~ .input-icon {
    color: #dc2626;
}

.floating-label {
    position: absolute;
    left: 50px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
    pointer-events: none;
    transition: all 0.3s ease;
}

.form-input:focus ~ .floating-label,
.form-input:not(:placeholder-shown) ~ .floating-label {
    transform: translateY(-32px) scale(0.85);
    color: #dc2626;
    left: 16px;
}

.password-toggle {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #dc2626;
}

.error-message {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ef4444;
    font-size: 14px;
    margin-top: 8px;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    margin-right: 12px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-container input:checked ~ .checkmark {
    background: #dc2626;
    border-color: #dc2626;
}

.checkbox-container input:checked ~ .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.checkbox-text {
    font-size: 14px;
    color: #6b7280;
}

.login-button {
    width: 100%;
    padding: 16px 24px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

.login-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.login-button:hover::before {
    left: 100%;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px -5px rgba(220, 38, 38, 0.4);
}

.login-button:active {
    transform: translateY(0);
}

.button-icon {
    transition: transform 0.3s ease;
}

.login-button:hover .button-icon {
    transform: translateX(4px);
}

.staff-login-notice {
    border-top: 1px solid #e5e7eb;
    padding-top: 20px;
    margin-top: 20px;
}

.staff-link {
    color: #dc2626;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.staff-link:hover {
    color: #991b1b;
    text-decoration: underline;
}

/* Hide any potential sidebar or navigation */
.sidebar, .navbar, .nav, .navigation {
    display: none !important;
}

@media (max-width: 640px) {
    .login-content {
        max-width: 100%;
        padding: 16px;
    }
    
    .login-form-container,
    .login-header {
        padding: 32px 24px;
    }
    
    .brand-title {
        font-size: 24px;
    }
    
    .form-title {
        font-size: 20px;
    }
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordEye = document.getElementById('password-eye');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordEye.classList.remove('fa-eye');
        passwordEye.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordEye.classList.remove('fa-eye-slash');
        passwordEye.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add focus animations
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Council Admin Login - Council ERP')

@section('content')
<div class="login-container">
    <div class="login-background">
        <div class="geometric-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>
    
    <div class="login-content">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1 class="brand-title">Council Admin</h1>
                    <p class="brand-subtitle">Administrative Portal</p>
                </div>
            </div>

            <div class="login-form-container">
                <h2 class="form-title">Administrator Login</h2>
                <p class="form-subtitle">Access the administrative dashboard</p>

                <form method="POST" action="{{ route('admin.login.submit') }}" class="login-form">
                    @csrf

                    <div class="input-group">
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" 
                                   class="form-input @error('email') error @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="Admin Email Address">
                            <label for="email" class="floating-label">Admin Email Address</label>
                        </div>
                        @error('email')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" type="password" 
                                   class="form-input @error('password') error @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Admin Password">
                            <label for="password" class="floating-label">Admin Password</label>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span class="checkbox-text">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="login-button">
                        <span class="button-text">Admin Sign In</span>
                        <i class="fas fa-arrow-right button-icon"></i>
                    </button>
                </form>

                <div class="admin-login-notice">
                    <p class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            This is for administrators only. 
                            <br>
                            <strong>Department Staff:</strong> Please use the 
                            <a href="{{ route('login') }}" class="admin-link">Main Login Portal</a>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Use the same styles as the main login page */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    overflow-x: hidden;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    min-height: 100vh;
}

.login-container {
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
}

.login-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(239, 68, 68, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(185, 28, 28, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(153, 27, 27, 0.3) 0%, transparent 50%);
    animation: backgroundPulse 10s ease-in-out infinite;
}

@keyframes backgroundPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.geometric-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    animation: float 15s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 5s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 10s;
}

.shape-4 {
    width: 120px;
    height: 120px;
    top: 30%;
    right: 40%;
    animation-delay: 7s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    33% {
        transform: translateY(-30px) rotate(120deg);
    }
    66% {
        transform: translateY(20px) rotate(240deg);
    }
}

.login-content {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 450px;
    padding: 20px;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 35px 60px -12px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.3);
}

.login-header {
    padding: 40px 40px 20px;
    text-align: center;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    position: relative;
}

.login-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.logo-container {
    position: relative;
    z-index: 2;
}

.logo-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.logo-icon i {
    font-size: 30px;
    color: white;
}

.brand-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px;
    letter-spacing: -0.5px;
}

.brand-subtitle {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.login-form-container {
    padding: 40px;
}

.form-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px;
    text-align: center;
}

.form-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 32px;
    text-align: center;
}

.input-group {
    margin-bottom: 24px;
}

.input-wrapper {
    position: relative;
}

.form-input {
    width: 100%;
    padding: 16px 16px 16px 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #fafafa;
    color: #1a1a1a;
}

.form-input:focus {
    outline: none;
    border-color: #dc2626;
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.25);
}

.form-input.error {
    border-color: #ef4444;
    background: #fef2f2;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
    transition: color 0.3s ease;
}

.form-input:focus + .floating-label,
.form-input:focus ~ .input-icon {
    color: #dc2626;
}

.floating-label {
    position: absolute;
    left: 50px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
    pointer-events: none;
    transition: all 0.3s ease;
}

.form-input:focus ~ .floating-label,
.form-input:not(:placeholder-shown) ~ .floating-label {
    transform: translateY(-32px) scale(0.85);
    color: #dc2626;
    left: 16px;
}

.password-toggle {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #dc2626;
}

.error-message {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ef4444;
    font-size: 14px;
    margin-top: 8px;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    margin-right: 12px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-container input:checked ~ .checkmark {
    background: #dc2626;
    border-color: #dc2626;
}

.checkbox-container input:checked ~ .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.checkbox-text {
    font-size: 14px;
    color: #6b7280;
}

.login-button {
    width: 100%;
    padding: 16px 24px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

.login-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.login-button:hover::before {
    left: 100%;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px -5px rgba(220, 38, 38, 0.4);
}

.login-button:active {
    transform: translateY(0);
}

.button-icon {
    transition: transform 0.3s ease;
}

.login-button:hover .button-icon {
    transform: translateX(4px);
}

.admin-login-notice {
    border-top: 1px solid #e5e7eb;
    padding-top: 20px;
    margin-top: 20px;
}

.admin-link {
    color: #dc2626;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.admin-link:hover {
    color: #991b1b;
    text-decoration: underline;
}

@media (max-width: 640px) {
    .login-content {
        max-width: 100%;
        padding: 16px;
    }
    
    .login-form-container,
    .login-header {
        padding: 32px 24px;
    }
    
    .brand-title {
        font-size: 24px;
    }
    
    .form-title {
        font-size: 20px;
    }
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordEye = document.getElementById('password-eye');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordEye.classList.remove('fa-eye');
        passwordEye.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordEye.classList.remove('fa-eye-slash');
        passwordEye.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>
@endsection
