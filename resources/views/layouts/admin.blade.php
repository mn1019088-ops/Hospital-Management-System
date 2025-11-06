<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - HMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: #3498db;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #34495e;
            color: #3498db;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f0f4f8; 
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-hospital-alt me-2"></i>MediCare </h4>
            <small class="text-muted">Admin Panel</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}" href="{{ route('admin.doctors') }}">
                    <i class="fas fa-user-md me-2"></i>Doctors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}" href="{{ route('admin.patients') }}">
                    <i class="fas fa-procedures me-2"></i>Patients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}" href="{{ route('admin.appointments') }}">
                    <i class="fas fa-calendar-check me-2"></i>Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}" href="{{ route('admin.departments') }}">
                    <i class="fas fa-building me-2"></i>Departments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.rooms*') ? 'active' : '' }}" href="{{ route('admin.rooms') }}">
                    <i class="fas fa-bed me-2"></i>Rooms
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.medical-records*') ? 'active' : '' }}" href="{{ route('admin.medical-records') }}">
                    <i class="fas fa-file-medical me-2"></i>Medical Records
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.receptions*') ? 'active' : '' }}" href="{{ route('admin.receptions') }}">
                    <i class="fas fa-user-tie me-2"></i>Reception Staff
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
            <div class="container-fluid">
                <div class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::guard('admin')->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Toast Notifications -->
    <div id="toast-container">
        @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 5000 }).show());
        });
    </script>
    @yield('scripts')
</body>
</html>
