<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, external-width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Council ERP System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #4facfe;
            --success-color: #00f2fe;
            --warning-color: #fa709a;
            --danger-color: #ff6b6b;
            --info-color: #38bdf8;

            --sidebar-width: 320px;
            --topbar-height: 80px;

            --bg-primary: #0a0e27;
            --bg-secondary: #1a1f36;
            --bg-tertiary: #252a47;
            --bg-quaternary: #2d3258;

            --text-primary: #ffffff;
            --text-secondary: #b8bcc8;
            --text-accent: #667eea;
            --text-muted: #8b92a9;

            --border-color: rgba(255, 255, 255, 0.12);
            --border-hover: rgba(255, 255, 255, 0.25);

            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.15);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.25);
            --shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.35);

            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 20px;
            --radius-xl: 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            color: var(--text-primary);
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .main-wrapper::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(75, 172, 254, 0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-tertiary) 50%, var(--bg-quaternary) 100%);
            backdrop-filter: blur(20px);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid var(--border-color);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            pointer-events: none;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.03);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--accent-color), var(--primary-color));
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, #ffffff, #e3e8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-header .subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
            font-weight: 500;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .nav-section {
            padding: 1rem 0 0.5rem;
            position: relative;
        }

        .nav-section-title {
            padding: 1rem 1.5rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(75, 172, 254, 0.05));
            border-left: 3px solid var(--accent-color);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .nav-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, var(--accent-color), transparent);
            opacity: 0.6;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.25rem;
            border: 1px solid transparent;
            backdrop-filter: blur(10px);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(75, 172, 254, 0.15));
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, rgba(75, 172, 254, 0.15), rgba(0, 242, 254, 0.1));
            color: #ffffff;
            transform: translateX(8px) translateY(-2px) scale(1.02);
            border-color: rgba(75, 172, 254, 0.3);
            box-shadow: var(--shadow-md);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color)) !important;
            color: white !important;
            transform: translateX(6px) translateY(-1px) scale(1.01);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255,255,255,0.2);
            animation: activeGlow 4s ease-in-out infinite alternate;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(180deg, #ffffff, rgba(255,255,255,0.7));
            border-radius: 2px 0 0 2px;
            box-shadow: 0 0 8px rgba(255,255,255,0.5);
        }

        .nav-section.collapsible .nav-section-title {
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            padding-right: 3.5rem;
        }

        .nav-section.collapsible .nav-section-title:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(75, 172, 254, 0.1));
            color: #ffffff;
            box-shadow: var(--shadow-sm);
        }

        .nav-section.collapsible .nav-section-title::before {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--accent-color);
            font-size: 1rem;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(75, 172, 254, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(75, 172, 254, 0.3);
        }

        .nav-section.collapsible.collapsed .nav-section-title::before {
            transform: translateY(-50%) rotate(-90deg);
            background: rgba(139, 146, 169, 0.2);
            border-color: rgba(139, 146, 169, 0.3);
            color: var(--text-muted);
        }

        .nav-section.collapsible.collapsed .nav-items {
            max-height: 0 !important;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding-top: 0;
            padding-bottom: 0;
            margin-top: 0;
            margin-bottom: 0;
        }

        .nav-items {
            max-height: 2000px;
            opacity: 1;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
        }

        .nav-link i {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.1rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .nav-link:hover i {
            transform: scale(1.15) rotate(5deg);
            color: #ffffff;
        }

        .nav-link.active i {
            color: #ffffff;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            transform: scale(1.1);
        }

        .nav-link span {
            position: relative;
            z-index: 1;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .nav-link .badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--danger-color), #ff5252);
            color: white;
            font-weight: 600;
            position: relative;
            z-index: 1;
            box-shadow: var(--shadow-sm);
            animation: pulse 2s ease-in-out infinite;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            background: linear-gradient(135deg, rgba(255,255,255,0.97) 0%, rgba(248,250,252,0.95) 100%);
            backdrop-filter: blur(20px);
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .topbar {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.9) 100%);
            backdrop-filter: blur(20px);
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            margin: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .topbar h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--bg-primary);
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .search-container {
            position: relative;
            max-width: 450px;
            flex: 1;
            margin: 0 2rem;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3.5rem;
            border: 2px solid rgba(102, 126, 234, 0.15);
            border-radius: var(--radius-lg);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            color: var(--bg-primary);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            background: rgba(255,255,255,0.95);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), var(--shadow-md);
            outline: none;
            transform: translateY(-1px) scale(1.01);
        }

        .search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-icon {
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(75, 172, 254, 0.05));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--primary-color);
            cursor: pointer;
        }

        .notification-icon:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: scale(1.1) translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, var(--danger-color), #ff5252);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.4);
            animation: pulse 2s ease-in-out infinite;
        }

        .content-area {
            padding: 2rem;
        }

        .user-menu {
            position: relative;
            margin-left: auto;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            border: 3px solid rgba(255,255,255,0.3);
        }

        .user-avatar:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Enhanced Form Styling */
        .form-control, .form-select {
            border: 2px solid rgba(102, 126, 234, 0.15);
            border-radius: var(--radius-md);
            padding: 0.875rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), var(--shadow-sm);
            transform: translateY(-1px) scale(1.01);
            background: rgba(255,255,255,0.95);
        }

        .form-label {
            font-weight: 600;
            color: var(--bg-primary);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .btn {
            border-radius: var(--radius-md);
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            box-shadow: var(--shadow-md);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color), var(--accent-color));
            box-shadow: var(--shadow-md);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #fee140);
            box-shadow: var(--shadow-md);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ff5252);
            box-shadow: var(--shadow-md);
        }

        /* Enhanced Card Styling */
        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(20px);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--success-color));
        }

        .card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: var(--shadow-xl);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            padding: 1.5rem 2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-body {
            padding: 2rem;
        }

        /* Alert Improvements */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 1.25rem 1.75rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.1), rgba(75, 172, 254, 0.05));
            border-left-color: var(--success-color);
            color: #0c5460;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 82, 82, 0.05));
            border-left-color: var(--danger-color);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(250, 112, 154, 0.1), rgba(254, 225, 64, 0.05));
            border-left-color: var(--warning-color);
            color: #856404;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.1), rgba(75, 172, 254, 0.05));
            border-left-color: var(--info-color);
            color: #0c5460;
        }

        /* Animations */
        @keyframes activeGlow {
            0% { box-shadow: var(--shadow-lg); }
            100% { box-shadow: var(--shadow-xl), 0 0 30px rgba(75, 172, 254, 0.3); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
                box-shadow: var(--shadow-xl);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
                animation: slideInLeft 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(10, 14, 39, 0.8);
                backdrop-filter: blur(8px);
                z-index: 1049;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
                animation: fadeIn 0.3s ease-out;
            }

            .topbar {
                margin: 0 0.75rem;
                padding: 1rem 1.25rem;
            }

            .content-area {
                padding: 1.25rem;
            }

            .search-container {
                display: none;
            }

            @keyframes slideInLeft {
                from { transform: translateX(-100%); }
                to { transform: translateX(0); }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }

        /* Ensure sidebar stays visible on larger screens */
        @media (min-width: 769px) {
            .sidebar {
                position: fixed !important;
                transform: translateX(0) !important;
            }

            .main-content {
                margin-left: var(--sidebar-width) !important;
            }
        }

        @yield('additional-styles')
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="main-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-city"></i> Council ERP</h3>
                <div class="subtitle">Management Portal</div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Dashboard</div>
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') || request()->is('/') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('finance.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Financial Management</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('finance.index') }}" class="nav-link {{ request()->routeIs('finance.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Finance Dashboard</span>
                        </a>
                    </div>

                    <!-- Core Accounting -->
                    <div class="nav-item">
                        <a href="{{ route('finance.chart-of-accounts.index') }}" class="nav-link {{ request()->routeIs('finance.chart-of-accounts.*') ? 'active' : '' }}">
                            <i class="fas fa-list-alt"></i>
                            <span>Chart of Accounts</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.general-ledger.index') }}" class="nav-link {{ request()->routeIs('finance.general-ledger.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>General Ledger</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.cashbook.index') }}" class="nav-link {{ request()->routeIs('finance.cashbook.*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Cashbook Management</span>
                        </a>
                    </div>

                    <!-- Revenue Management -->
                    <div class="nav-item">
                        <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link {{ request()->routeIs('finance.accounts-receivable.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Accounts Receivable</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.debtors.index') }}" class="nav-link {{ request()->routeIs('finance.debtors.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Debtors Management</span>
                        </a>
                    </div>

                    <!-- FDMS Compliance -->
                    <div class="nav-item">
                        <a href="{{ route('finance.fdms.index') }}" class="nav-link {{ request()->routeIs('finance.fdms.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>FDMS Receipting</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.pos.index') }}" class="nav-link {{ request()->routeIs('finance.pos.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Point of Sale</span>
                        </a>
                    </div>

                    <!-- Payment Management -->
                    <div class="nav-item">
                        <a href="{{ route('finance.vouchers.index') }}" class="nav-link {{ request()->routeIs('finance.vouchers.*') ? 'active' : '' }}">
                            <i class="fas fa-file-contract"></i>
                            <span>Voucher Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.bank-manager.index') }}" class="nav-link {{ request()->routeIs('finance.bank-manager.*') ? 'active' : '' }}">
                            <i class="fas fa-university"></i>
                            <span>Bank Manager</span>
                        </a>
                    </div>

                    <!-- Asset Management -->
                    <div class="nav-item">
                        <a href="{{ route('finance.fixed-assets.index') }}" class="nav-link {{ request()->routeIs('finance.fixed-assets.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Fixed Asset Register</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.procurement.index') }}" class="nav-link {{ request()->routeIs('finance.procurement.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Procurement & Stores</span>
                        </a>
                    </div>

                    <!-- Multicurrency -->
                    <div class="nav-item">
                        <a href="{{ route('finance.multicurrency.index') }}" class="nav-link {{ request()->routeIs('finance.multicurrency.*') ? 'active' : '' }}">
                            <i class="fas fa-coins"></i>
                            <span>Multicurrency</span>
                        </a>
                    </div>

                    <!-- IPSAS Reports -->
                    <div class="nav-item">
                        <a href="{{ route('finance.ipsas-reports.index') }}" class="nav-link {{ request()->routeIs('finance.ipsas-reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>IPSAS Financial Statements</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.program-reports.index') }}" class="nav-link {{ request()->routeIs('finance.program-reports.*') ? 'active' : '' }}">
                            <i class="fas fa-project-diagram"></i>
                            <span>Program Based Reports</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('finance.business-intelligence.index') }}" class="nav-link {{ request()->routeIs('finance.business-intelligence.*') ? 'active' : '' }}">
                            <i class="fas fa-brain"></i>
                            <span>Business Intelligence</span>
                        </a>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.tax-management.index') }}">
                            <i class="fas fa-percentage me-2"></i>Tax Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.fiscalization.index') }}">
                            <i class="fas fa-receipt me-2"></i>ZIMRA Fiscalization
                            @php
                                try {
                                    $pendingReceipts = \App\Models\Finance\FiscalReceipt::where('compliance_status', 'pending')->count();
                                } catch (\Exception $e) {
                                    $pendingReceipts = 0;
                                }
                            @endphp
                            @if($pendingReceipts > 0)
                                <span class="badge badge-warning badge-pill ml-auto">{{ $pendingReceipts }}</span>
                            @endif
                        </a>
                    </li>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('billing.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Revenue & Billing</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.index') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Municipal Billing</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('billing.customers.index') }}" class="nav-link {{ request()->routeIs('billing.customers.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Customer Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('billing.services.index') }}" class="nav-link {{ request()->routeIs('billing.services.*') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            <span>Service Management</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('housing.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Housing & Properties</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('housing.index') }}" class="nav-link {{ request()->routeIs('housing.index') || request()->is('housing') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Housing Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('housing.applications.index') }}" class="nav-link {{ request()->routeIs('housing.applications.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Applications</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('housing.properties.index') }}" class="nav-link {{ request()->routeIs('housing.properties.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Properties</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('housing.waiting-list.index') }}" class="nav-link {{ request()->routeIs('housing.waiting-list.*') ? 'active' : '' }}">
                            <i class="fas fa-clock"></i>
                            <span>Waiting List</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('housing.allocations.index') }}" class="nav-link {{ request()->routeIs('housing.allocations.*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i>
                            <span>Allocations</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('planning.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Planning & Development</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('planning.index') }}" class="nav-link {{ request()->routeIs('planning.*') ? 'active' : '' }}">
                            <i class="fas fa-drafting-compass"></i>
                            <span>Planning Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('planning.applications.index') }}" class="nav-link {{ request()->routeIs('planning.applications.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Planning Applications</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('water.*', 'utilities.*', 'engineering.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Utilities & Infrastructure</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('water.index') }}" class="nav-link {{ request()->routeIs('water.index') || request()->is('water') ? 'active' : '' }}">
                            <i class="fas fa-tint"></i>
                            <span>Water Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('utilities.index') }}" class="nav-link {{ request()->routeIs('utilities.index') || request()->is('utilities') ? 'active' : '' }}">
                            <i class="fas fa-plug"></i>
                            <span>Utilities & Infrastructure</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('engineering.index') }}" class="nav-link {{ request()->routeIs('engineering.index') || request()->is('engineering') ? 'active' : '' }}">
                            <i class="fas fa-hard-hat"></i>
                            <span>Engineering</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('inventory.*', 'cemeteries.*', 'parking.*', 'markets.*', 'survey.*', 'property.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Operations</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                            <i class="fas fa-boxes"></i>
                            <span>Inventory</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('cemeteries.index') }}" class="nav-link {{ request()->routeIs('cemeteries.*') ? 'active' : '' }}">
                            <i class="fas fa-cross"></i>
                            <span>Cemetery Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('parking.index') }}" class="nav-link {{ request()->routeIs('parking.*') ? 'active' : '' }}">
                            <i class="fas fa-parking"></i>
                            <span>Parking Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('markets.index') }}" class="nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <span>Markets</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('survey.index') }}" class="nav-link {{ request()->routeIs('survey.*') ? 'active' : '' }}">
                            <i class="fas fa-map"></i>
                            <span>Survey Services</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('property.index') }}" class="nav-link {{ request()->routeIs('property.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Property Management</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('committee.*', 'licensing.*', 'events.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Governance</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('committee.index') }}" class="nav-link {{ request()->routeIs('committee.*') ? 'active' : '' }}">
                            <i class="fas fa-gavel"></i>
                            <span>Committee Management</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('licensing.index') }}" class="nav-link {{ request()->routeIs('licensing.*') ? 'active' : '' }}">
                            <i class="fas fa-certificate"></i>
                            <span>Licensing</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('events.permits.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>Event Permits</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('health.*', 'hr.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Human Resources & Health</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('hr.index') }}" class="nav-link {{ request()->routeIs('hr.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Human Resources</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('health.index') }}" class="nav-link {{ request()->routeIs('health.*') ? 'active' : '' }}">
                            <i class="fas fa-heartbeat"></i>
                            <span>Health Services</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section collapsible {{ request()->routeIs('reports.*') ? '' : 'collapsed' }}">
                <div class="nav-section-title">Reports & Analytics</div>
                <div class="nav-items">
                    <div class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i>
                            <span>Reports Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('reports.revenue') }}" class="nav-link {{ request()->routeIs('reports.revenue') ? 'active' : '' }}">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Revenue Reports</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('reports.analytics.index') }}" class="nav-link {{ request()->routeIs('reports.analytics*') ? 'active' : '' }}">
                            <i class="fas fa-analytics"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Administration</div>
                <div class="nav-item">
                    <a href="{{ route('administration.crm.index') }}" class="nav-link {{ request()->routeIs('administration.crm.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span>CRM</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('administration.core-modules.index') }}" class="nav-link {{ request()->routeIs('administration.core-modules.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Core Modules</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('administration.users.index') }}" class="nav-link {{ request()->routeIs('administration.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>User Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('administration.departments.index') }}" class="nav-link {{ request()->routeIs('administration.departments.*') ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i>
                        <span>Departments</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.offices.index') }}" class="nav-link {{ request()->routeIs('admin.offices.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Offices</span>
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <div class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link d-md-none me-2" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0">
                        @yield('page-title', 'Dashboard')
                    </h4>
                </div>

                <div class="search-container d-none d-md-block">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search customers, invoices, applications...">
                </div>

                <div class="topbar-actions">
                    <button class="notification-icon" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>

                    <div class="dropdown">
                        <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : '' }}
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->check())
                            <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Enhanced JavaScript for Better UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const navLinks = document.querySelectorAll('.nav-link');
            const collapsibleSections = document.querySelectorAll('.nav-section.collapsible');

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.7';
                    }
                });
            });

            // Add smooth transitions to cards with staggered animation
            const cards = document.querySelectorAll('.card, .stats-card, .alert');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });

            // Enhanced search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02) translateY(-2px)';
                    this.parentElement.style.boxShadow = 'var(--shadow-lg)';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                    this.parentElement.style.boxShadow = '';
                });

                // Add search suggestions (placeholder for future implementation)
                searchInput.addEventListener('input', function() {
                    const query = this.value;
                    if (query.length > 2) {
                        // Future: Implement real-time search suggestions
                        console.log('Searching for:', query);
                    }
                });
            }

            // Mobile sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                    sidebarOverlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
                });
            }

            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });

            // Enhanced nav link interactions with ripple effect
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.4);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        left: ${x}px;
                        top: ${y}px;
                        width: ${size}px;
                        height: ${size}px;
                        pointer-events: none;
                        z-index: 1;
                    `;

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);

                    // Close mobile sidebar after clicking
                    if (window.innerWidth <= 768) {
                        setTimeout(() => {
                            sidebar.classList.remove('mobile-open');
                            sidebarOverlay.classList.remove('show');
                            document.body.style.overflow = '';
                        }, 150);
                    }
                });
            });

            // Collapsible sidebar sections with improved state management
            collapsibleSections.forEach(section => {
                const title = section.querySelector('.nav-section-title');
                const sectionText = title.textContent.trim().replace(/\s+/g, '-').toLowerCase();

                title.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    section.classList.toggle('collapsed');

                    // Save state to localStorage with animation
                    if (section.classList.contains('collapsed')) {
                        localStorage.setItem(`sidebar-${sectionText}`, 'collapsed');
                    } else {
                        localStorage.removeItem(`sidebar-${sectionText}`);
                    }
                });

                // Restore state from localStorage
                if (localStorage.getItem(`sidebar-${sectionText}`) === 'collapsed') {
                    section.classList.add('collapsed');
                }
            });

            // Auto-expand sections with active links
            navLinks.forEach(link => {
                if (link.classList.contains('active')) {
                    const section = link.closest('.nav-section.collapsible');
                    if (section) {
                        section.classList.remove('collapsed');
                    }
                }
            });

            // Enhanced button interactions
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.02)';
                });

                button.addEventListener('mouseleave', function() {
                    if (!this.disabled) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });

            // Form enhancements with better visual feedback
            const inputs = document.querySelectorAll('.form-control, .form-select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                    if (this.parentElement.querySelector('label')) {
                        this.parentElement.querySelector('label').style.color = 'var(--primary-color)';
                    }
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.parentElement.querySelector('label')) {
                        this.parentElement.querySelector('label').style.color = '';
                    }
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href && href.length > 1) {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            // Enhanced notification interactions
            const notificationIcon = document.querySelector('.notification-icon');
            if (notificationIcon) {
                notificationIcon.addEventListener('click', function() {
                    // Future: Show notifications dropdown
                    console.log('Show notifications');
                });
            }

            // Add tooltip functionality
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    // Future: Implement custom tooltips
                });
            });
        });

        // Global functions for enhanced UX
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 350px;
                animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: var(--shadow-lg);
                border-radius: var(--radius-md);
                backdrop-filter: blur(20px);
            `;
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'} me-3"></i>
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    setTimeout(() => notification.remove(), 400);
                }
            }, 5000);
        }

        // Add additional animation keyframes
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slideOutRight {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(100%);
                }
            }

            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .focused {
                transform: translateY(-1px);
            }
        `;
        document.head.appendChild(additionalStyles);
    </script>

    @yield('scripts')
</body>
</html>