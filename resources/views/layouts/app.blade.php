<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Council ERP System')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-primary: #0f0f23;
            --bg-secondary: #1a1a3a;
            --bg-tertiary: #252545;
            --bg-quaternary: #2d2d55;
            --text-primary: #ffffff;
            --text-secondary: #b8b8cc;
            --text-accent: #667eea;
            --border-color: rgba(255, 255, 255, 0.1);
            --border-hover: rgba(102, 126, 234, 0.3);
            --accent-color: #667eea;
            --danger-color: #f5576c;
            --success-color: #4facfe;
            --warning-color: #fa709a;
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.2);
            --shadow-xl: 0 16px 32px rgba(0, 0, 0, 0.25);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-primary);
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
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

        .sidebar-brand {
            padding: 1.5rem;
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .sidebar-brand-icon {
            font-size: 2rem;
            margin-right: 0.75rem;
            color: var(--text-accent);
        }

        .sidebar-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-divider {
            border-top: 1px solid var(--border-color);
            margin: 1rem 0;
        }

        .sidebar-heading {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-secondary);
            background: rgba(102, 126, 234, 0.1);
            border-left: 3px solid var(--accent-color);
            margin: 0.5rem 1rem 0.5rem 0;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.6s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(102, 126, 234, 0.2);
            border-color: var(--border-hover);
            transform: translateX(8px);
            box-shadow: var(--shadow-lg);
        }

        .nav-link.active {
            color: var(--text-primary);
            background: var(--primary-gradient);
            border-color: var(--text-accent);
            box-shadow: var(--shadow-lg);
            font-weight: 600;
        }

        .nav-link i {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.1rem;
            text-align: center;
            transition: all 0.3s ease;
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

        /* Collapse menu styles */
        .collapse-inner {
            background: rgba(15, 15, 35, 0.9);
            border-radius: var(--radius-md);
            margin: 0.5rem 0;
            padding: 0.5rem 0;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .collapse-header {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-accent);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0.5rem;
        }

        .collapse-item {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            margin: 0.25rem 0.5rem;
            transition: all 0.3s ease;
        }

        .collapse-item:hover {
            color: var(--text-primary);
            background: rgba(102, 126, 234, 0.2);
            transform: translateX(4px);
        }

        /* Content Wrapper */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            background: linear-gradient(135deg, rgba(255,255,255,0.97) 0%, rgba(248,250,252,0.95) 100%);
            backdrop-filter: blur(20px);
            min-height: 100vh;
            position: relative;
        }

        /* Topbar */
        .topbar {
            background: rgba(15, 15, 35, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar .nav-link {
            color: var(--text-secondary);
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            transition: color 0.3s ease;
        }

        .topbar .nav-link:hover {
            color: var(--text-accent);
        }

        .img-profile {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Main content area */
        .container-fluid {
            padding: 2rem;
        }

        /* Card styles */
        .card {
            background: rgba(26, 26, 58, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: var(--border-hover);
            box-shadow: var(--shadow-xl);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
            color: var(--text-secondary);
        }

        /* Border utilities */
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        /* Text utilities */
        .text-xs {
            font-size: 0.7rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }

            .sidebar.toggled {
                margin-left: 0;
            }

            #content-wrapper {
                margin-left: 0;
            }

            #content-wrapper.toggled {
                margin-left: 280px;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Council ERP</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Core Modules
            </div>

            <!-- Nav Item - Finance -->
            <li class="nav-item {{ Request::routeIs('finance.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinance" aria-expanded="true" aria-controls="collapseFinance">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Finance</span>
                </a>
                <div id="collapseFinance" class="collapse {{ Request::routeIs('finance.*') ? 'show' : '' }}" aria-labelledby="headingFinance" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Finance Management:</h6>
                        <a class="collapse-item" href="{{ route('finance.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('finance.chart-of-accounts.index') }}">Chart of Accounts</a>
                        <a class="collapse-item" href="{{ route('finance.general-ledger.index') }}">General Ledger</a>
                        <a class="collapse-item" href="{{ route('finance.budgets.index') }}">Budgets</a>
                        <a class="collapse-item" href="{{ route('finance.invoices.index') }}">Invoices</a>
                        <a class="collapse-item" href="{{ route('finance.reports.index') }}">Reports</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Housing -->
            <li class="nav-item {{ Request::routeIs('housing.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseHousing" aria-expanded="true" aria-controls="collapseHousing">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Housing</span>
                </a>
                <div id="collapseHousing" class="collapse {{ Request::routeIs('housing.*') ? 'show' : '' }}" aria-labelledby="headingHousing" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Housing Management:</h6>
                        <a class="collapse-item" href="{{ route('housing.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('housing.properties.index') }}">Properties</a>
                        <a class="collapse-item" href="{{ route('housing.applications.index') }}">Applications</a>
                        <a class="collapse-item" href="{{ route('housing.allocations.index') }}">Allocations</a>
                        <a class="collapse-item" href="{{ route('housing.waiting-list.index') }}">Waiting List</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Water -->
            <li class="nav-item {{ Request::routeIs('water.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseWater" aria-expanded="true" aria-controls="collapseWater">
                    <i class="fas fa-fw fa-tint"></i>
                    <span>Water</span>
                </a>
                <div id="collapseWater" class="collapse {{ Request::routeIs('water.*') ? 'show' : '' }}" aria-labelledby="headingWater" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Water Management:</h6>
                        <a class="collapse-item" href="{{ route('water.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('water.connections.index') }}">Connections</a>
                        <a class="collapse-item" href="{{ route('water.meters.index') }}">Meters</a>
                        <a class="collapse-item" href="{{ route('water.billing.index') }}">Billing</a>
                        <a class="collapse-item" href="{{ route('water.quality.index') }}">Quality Tests</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Health -->
            <li class="nav-item {{ Request::routeIs('health.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseHealth" aria-expanded="true" aria-controls="collapseHealth">
                    <i class="fas fa-fw fa-heartbeat"></i>
                    <span>Health</span>
                </a>
                <div id="collapseHealth" class="collapse {{ Request::routeIs('health.*') ? 'show' : '' }}" aria-labelledby="headingHealth" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Health Services:</h6>
                        <a class="collapse-item" href="{{ route('health.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('health.facilities.index') }}">Facilities</a>
                        <a class="collapse-item" href="{{ route('health.inspections.index') }}">Inspections</a>
                        <a class="collapse-item" href="{{ route('health.permits.index') }}">Permits</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - HR -->
            <li class="nav-item {{ Request::routeIs('hr.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseHR" aria-expanded="true" aria-controls="collapseHR">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Human Resources</span>
                </a>
                <div id="collapseHR" class="collapse {{ Request::routeIs('hr.*') ? 'show' : '' }}" aria-labelledby="headingHR" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">HR Management:</h6>
                        <a class="collapse-item" href="{{ route('hr.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('hr.employees.index') }}">Employees</a>
                        <a class="collapse-item" href="{{ route('hr.attendance.index') }}">Attendance</a>
                        <a class="collapse-item" href="{{ route('hr.departments.index') }}">Departments</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Committee -->
            <li class="nav-item {{ Request::routeIs('committee.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCommittee" aria-expanded="true" aria-controls="collapseCommittee">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>Committee</span>
                </a>
                <div id="collapseCommittee" class="collapse {{ Request::routeIs('committee.*') ? 'show' : '' }}" aria-labelledby="headingCommittee" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Committee Management:</h6>
                        <a class="collapse-item" href="{{ route('committee.index') }}">Overview</a>
                        <a class="collapse-item" href="{{ route('committee.committees.index') }}">Committees</a>
                        <a class="collapse-item" href="{{ route('committee.meetings.index') }}">Meetings</a>
                        <a class="collapse-item" href="{{ route('committee.agendas.index') }}">Agendas</a>
                        <a class="collapse-item" href="{{ route('committee.minutes.index') }}">Minutes</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Additional Services
            </div>

            <!-- Nav Item - Engineering -->
            <li class="nav-item {{ Request::routeIs('engineering.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('engineering.index') }}">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Engineering</span>
                </a>
            </li>

            <!-- Nav Item - Licensing -->
            <li class="nav-item {{ Request::routeIs('licensing.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('licensing.index') }}">
                    <i class="fas fa-fw fa-certificate"></i>
                    <span>Licensing</span>
                </a>
            </li>

            <!-- Nav Item - Property Tax -->
            <li class="nav-item {{ Request::routeIs('property-tax.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('property-tax.index') }}">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Property Tax</span>
                </a>
            </li>

            <!-- Nav Item - Administration -->
            <li class="nav-item {{ Request::routeIs('administration.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('administration.index') }}">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>CRM</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Administration
            </div>

            <!-- Nav Item - Council Admin -->
            @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="nav-item {{ Request::routeIs('council-admin.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="true" aria-controls="collapseAdmin">
                    <i class="fas fa-fw fa-crown"></i>
                    <span>Council Admin</span>
                </a>
                <div id="collapseAdmin" class="collapse {{ Request::routeIs('council-admin.*') ? 'show' : '' }}" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">System Management:</h6>
                        <a class="dropdown-item" href="{{ route('council-admin.dashboard') }}">Admin Dashboard</a>
                        <a class="collapse-item" href="{{ route('council-admin.modules.index') }}">Module Management</a>
                        <a class="collapse-item" href="{{ route('council-admin.users.index') }}">User Management</a>
                    </div>
                </div>
            </li>
            @endif

            <!-- Nav Item - Reports -->
            <li class="nav-item {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.index') }}">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'Guest' }}</span>
                                <img class="img-profile rounded-circle" src="{{ Auth::user()->profile_photo_url ?? asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                <a class="dropdown-item" href="{{ route('council-admin.dashboard') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Council ERP System 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('accordionSidebar');
            const contentWrapper = document.getElementById('content-wrapper');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('toggled');
                    contentWrapper.classList.toggle('toggled');
                });
            }

            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('toggled');
                    contentWrapper.classList.toggle('toggled');
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(event.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            });

            // Add animation classes to elements
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>