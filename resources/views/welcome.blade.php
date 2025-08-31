@if(auth()->check())
    @extends('layouts.admin')
@else
    @extends('layouts.app')
@endif

@section('title', 'Welcome to Municipal ERP')

@section('content')
<div class="container-fluid p-0">
    <div class="welcome-hero">
        <!-- Animated background particles -->
        <div class="particles-container">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="hero-content text-center">
            <!-- Enhanced header with animated elements -->
            <div class="hero-header mb-5">
                <div class="logo-container mb-4">
                    <div class="logo-icon">
                        <i class="fas fa-city"></i>
                    </div>
                </div>
                <h1 class="hero-title mb-4">
                    <span class="title-main">Municipal ERP</span>
                    <span class="title-subtitle">System</span>
                </h1>
                <p class="hero-description">
                    Comprehensive Enterprise Resource Planning for <br class="d-none d-md-block">
                    <span class="highlight-text">Modern Municipal Operations</span>
                </p>
            </div>

            @auth
                <!-- Enhanced feature cards for authenticated users -->
                <div class="features-section">
                    <div class="section-header mb-5">
                        <h3 class="section-title">Explore Our Modules</h3>
                        <p class="section-subtitle">Streamlined municipal management at your fingertips</p>
                    </div>
                    
                    <div class="row justify-content-center g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="100">
                                <div class="card-icon-wrapper">
                                    <div class="card-icon finance">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Financial Management</h4>
                                    <p class="card-description">Complete accounting, budgeting, and financial reporting solutions with real-time insights</p>
                                    <div class="card-stats">
                                        <div class="stat-item">
                                            <span class="stat-number">15+</span>
                                            <span class="stat-label">Financial Reports</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <a href="{{ route('finance.index') }}" class="btn btn-enhanced btn-finance">
                                        <span>Explore Finance</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6">
                            <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="200">
                                <div class="card-icon-wrapper">
                                    <div class="card-icon hr">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Human Resources</h4>
                                    <p class="card-description">Employee management, payroll, and attendance tracking with advanced analytics</p>
                                    <div class="card-stats">
                                        <div class="stat-item">
                                            <span class="stat-number">24/7</span>
                                            <span class="stat-label">Employee Portal</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <a href="{{ route('hr.index') }}" class="btn btn-enhanced btn-hr">
                                        <span>Explore HR</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6">
                            <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="300">
                                <div class="card-icon-wrapper">
                                    <div class="card-icon admin">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Administration</h4>
                                    <p class="card-description">Customer relationship management and service delivery optimization</p>
                                    <div class="card-stats">
                                        <div class="stat-item">
                                            <span class="stat-number">100%</span>
                                            <span class="stat-label">Digital Workflow</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <a href="{{ route('administration.crm.index') }}" class="btn btn-enhanced btn-admin">
                                        <span>Explore CRM</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick stats section -->
                    <div class="quick-stats mt-5" data-aos="fade-up" data-aos-delay="400">
                        <div class="row justify-content-center">
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number">99.9%</h4>
                                        <p class="stat-label">Uptime</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number">100%</h4>
                                        <p class="stat-label">Secure</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number">24/7</h4>
                                        <p class="stat-label">Support</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number">1000+</h4>
                                        <p class="stat-label">Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Enhanced login section for guests -->
                <div class="auth-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="auth-content">
                        <div class="auth-icon mb-4">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 class="auth-title mb-3">Access Your Dashboard</h3>
                        <p class="auth-description mb-4">
                            Please log in to access the Municipal ERP system and manage your municipal operations
                        </p>
                        <div class="auth-buttons">
                            <a href="{{ route('login') }}" class="btn btn-enhanced btn-primary btn-lg">
                                <i class="fas fa-user"></i>
                                <span>User Login</span>
                            </a>
                            <a href="{{ route('admin.login') }}" class="btn btn-enhanced btn-secondary btn-lg">
                                <i class="fas fa-user-shield"></i>
                                <span>Admin Login</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Welcome Page Styles */
.welcome-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    color: white;
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

/* Animated particles background */
.particles-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle:nth-child(1) {
    width: 20px;
    height: 20px;
    left: 10%;
    top: 20%;
    animation-delay: 0s;
}

.particle:nth-child(2) {
    width: 30px;
    height: 30px;
    left: 20%;
    top: 60%;
    animation-delay: 1s;
}

.particle:nth-child(3) {
    width: 15px;
    height: 15px;
    left: 70%;
    top: 30%;
    animation-delay: 2s;
}

.particle:nth-child(4) {
    width: 25px;
    height: 25px;
    left: 80%;
    top: 70%;
    animation-delay: 3s;
}

.particle:nth-child(5) {
    width: 35px;
    height: 35px;
    left: 60%;
    top: 10%;
    animation-delay: 4s;
}

.particle:nth-child(6) {
    width: 18px;
    height: 18px;
    left: 30%;
    top: 80%;
    animation-delay: 5s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
    50% { transform: translateY(-20px) rotate(180deg); opacity: 0.7; }
}

/* Hero content */
.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 2rem;
}

/* Logo and header */
.logo-container {
    position: relative;
}

.logo-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    animation: logoGlow 2s ease-in-out infinite alternate;
}

.logo-icon i {
    font-size: 3.5rem;
    color: white;
}

@keyframes logoGlow {
    from { box-shadow: 0 0 20px rgba(255,255,255,0.3); }
    to { box-shadow: 0 0 40px rgba(255,255,255,0.6); }
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
}

.title-main {
    display: block;
    background: linear-gradient(45deg, #fff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.title-subtitle {
    display: block;
    font-size: 0.6em;
    font-weight: 300;
    color: rgba(255,255,255,0.8);
    margin-top: 0.5rem;
}

.hero-description {
    font-size: 1.3rem;
    font-weight: 300;
    color: rgba(255,255,255,0.9);
    line-height: 1.6;
}

.highlight-text {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 500;
}

/* Features section */
.features-section {
    margin-top: 4rem;
}

.section-header {
    text-align: center;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.section-subtitle {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    font-weight: 300;
}

/* Enhanced feature cards */
.enhanced-feature-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.enhanced-feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.enhanced-feature-card:hover::before {
    transform: translateX(100%);
}

.enhanced-feature-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(255,255,255,0.4);
}

.card-icon-wrapper {
    text-align: center;
    margin-bottom: 2rem;
}

.card-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
}

.card-icon::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(-45deg);
    transition: transform 0.6s ease;
}

.enhanced-feature-card:hover .card-icon::before {
    transform: rotate(-45deg) translate(100%, 100%);
}

.card-icon.finance {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.card-icon.hr {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.card-icon.admin {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.card-icon i {
    font-size: 2.2rem;
    color: white;
    position: relative;
    z-index: 1;
}

.card-content {
    flex: 1;
    text-align: center;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.card-description {
    font-size: 1rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.card-stats {
    margin-bottom: 2rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #FFD700;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.7);
    font-weight: 300;
}

/* Enhanced buttons */
.btn-enhanced {
    padding: 0.9rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    min-width: 160px;
    justify-content: center;
}

.btn-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-enhanced:hover::before {
    left: 100%;
}

.btn-finance {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
}

.btn-finance:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(79, 172, 254, 0.4);
    color: white;
}

.btn-hr {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
    color: white;
    box-shadow: 0 8px 20px rgba(67, 233, 123, 0.3);
}

.btn-hr:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(67, 233, 123, 0.4);
    color: white;
}

.btn-admin {
    background: linear-gradient(135deg, #fa709a, #fee140);
    color: white;
    box-shadow: 0 8px 20px rgba(250, 112, 154, 0.3);
}

.btn-admin:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(250, 112, 154, 0.4);
    color: white;
}

/* Quick stats */
.quick-stats {
    margin-top: 4rem;
}

.stat-box {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    text-align: center;
    margin-bottom: 1rem;
}

.stat-box:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
}

.stat-box .stat-icon {
    font-size: 2rem;
    color: #FFD700;
    margin-bottom: 1rem;
}

.stat-box .stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.stat-box .stat-label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
    font-weight: 300;
}

/* Auth section for guests */
.auth-section {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 4rem 3rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.auth-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 2px solid rgba(255,255,255,0.3);
}

.auth-icon i {
    font-size: 2rem;
    color: white;
}

.auth-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.auth-description {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
}

.auth-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary.btn-enhanced {
    background: linear-gradient(135deg, #667eea, #764ba2);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary.btn-enhanced {
    background: linear-gradient(135deg, #8B8B8B, #666666);
    box-shadow: 0 8px 20px rgba(139, 139, 139, 0.3);
}

/* Scroll indicator */
.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.scroll-arrow {
    width: 40px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bounce 2s infinite;
}

.scroll-arrow i {
    color: rgba(255,255,255,0.7);
    font-size: 1rem;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Responsive design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .title-subtitle {
        font-size: 0.7em;
    }
    
    .hero-description {
        font-size: 1.1rem;
    }
    
    .enhanced-feature-card {
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
    }
    
    .auth-section {
        padding: 3rem 2rem;
        margin: 1rem;
    }
    
    .auth-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-enhanced {
        width: 100%;
        max-width: 250px;
    }
    
    .logo-icon {
        width: 100px;
        height: 100px;
    }
    
    .logo-icon i {
        font-size: 3rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .enhanced-feature-card {
        padding: 1.5rem 1rem;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
    }
    
    .card-icon i {
        font-size: 1.8rem;
    }
}

/* AOS Animation Support */
[data-aos] {
    opacity: 0;
    transition-property: opacity, transform;
}

[data-aos].aos-animate {
    opacity: 1;
}

[data-aos="fade-up"] {
    transform: translateY(50px);
}

[data-aos="fade-up"].aos-animate {
    transform: translateY(0);
}
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@push('scripts')
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });
    
    // Add parallax effect to particles
    const particles = document.querySelectorAll('.particle');
    
    window.addEventListener('mousemove', function(e) {
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        particles.forEach((particle, index) => {
            const speed = (index + 1) * 0.5;
            const x = (mouseX - 0.5) * speed * 20;
            const y = (mouseY - 0.5) * speed * 20;
            
            particle.style.transform = `translate(${x}px, ${y}px)`;
        });
    });
    
    // Smooth scroll for scroll indicator
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            window.scrollTo({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        });
    }
    
    // Add loading animation to buttons
    const buttons = document.querySelectorAll('.btn-enhanced');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Don't prevent default as we want the navigation to work
            
            // Add loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>';
            this.style.pointerEvents = 'none';
            
            // Reset after a short delay (in case navigation is slow)
            setTimeout(() => {
                this.innerHTML = originalContent;
                this.style.pointerEvents = 'auto';
            }, 3000);
        });
    });
    
    // Add floating animation to feature cards on scroll
    window.addEventListener('scroll', function() {
        const cards = document.querySelectorAll('.enhanced-feature-card');
        const scrolled = window.pageYOffset;
        
        cards.forEach((card, index) => {
            const rate = scrolled * -0.5;
            const offset = index * 20;
            card.style.transform = `translateY(${rate + offset}px)`;
        });
    });
    
    // Add typewriter effect to title (optional enhancement)
    const titleMain = document.querySelector('.title-main');
    if (titleMain) {
        const text = titleMain.textContent;
        titleMain.textContent = '';
        
        let i = 0;
        const typeWriter = setInterval(() => {
            if (i < text.length) {
                titleMain.textContent += text.charAt(i);
                i++;
            } else {
                clearInterval(typeWriter);
            }
        }, 100);
    }
});
</script>
@endpush
