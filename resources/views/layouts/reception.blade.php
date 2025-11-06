<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - HMS Reception</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #3498db;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .logo h4 {
            color: #fff;
            margin: 0;
            font-weight: 700;
        }

        .sidebar .logo small {
            color: #34495e;
        }

        .sidebar .nav-link {
            color: #ecf1edff;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #2c3e50;
            color: #3498db;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .navbar .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }

        .btn-logout {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            padding: 12px 20px;
            color: #e74c3c;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #e74c3c;
            color: #fff;
        }

        .status-badge {
            font-size: 0.8em;
            padding: 0.35em 0.65em;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="sidebar">
        <div class="logo">
            <h4><i class="fas fa-hospital-alt me-2"></i>MediCare </h4>
            <small>Reception Panel</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.dashboard') ? 'active' : '' }}" href="{{ route('reception.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.patient-add') ? 'active' : '' }}" href="{{ route('reception.patient-add') }}">
                    <i class="fas fa-user-plus me-2"></i>Add Patient
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.patients.*') ? 'active' : '' }}" href="{{ route('reception.patients.list') }}">
                    <i class="fas fa-procedures me-2"></i>Patient List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.appointments*') ? 'active' : '' }}" href="{{ route('reception.appointments') }}">
                    <i class="fas fa-calendar-check me-2"></i>Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.rooms*') ? 'active' : '' }}" href="{{ route('reception.rooms') }}">
                    <i class="fas fa-bed me-2"></i>Room Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.room-allocations*') ? 'active' : '' }}" href="{{ route('reception.room-allocations') }}">
                    <i class="fas fa-door-closed me-2"></i>Room Allocations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reception.doctors*') ? 'active' : '' }}" href="{{ route('reception.doctors') }}">
                    <i class="fas fa-user-md me-2"></i>Doctors
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
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::guard('reception')->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
        </nav>

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Toast notifications -->
    <div id="toast-container">
        @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 5000 }).show());

            if ($.fn.select2) {
                $('.select2').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>