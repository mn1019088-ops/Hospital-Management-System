<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - HMS Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #3498db;
            position: fixed;
            width: 250px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #34495e;
            color: #3498db;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .doctor-bg {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4 p-3">
                        <h4 class="text-white">
                            <i class="fas fa-hospital-alt me-2"></i>MediCare 
                        </h4>
                        <small class="text-muted">Doctor Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" 
                               href="{{ route('doctor.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}" 
                               href="{{ route('doctor.appointments') }}">
                                <i class="fas fa-calendar-check me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.patients*') ? 'active' : '' }}" 
                               href="{{ route('doctor.patients') }}">
                                <i class="fas fa-procedures me-2"></i>My Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.medical-records*') ? 'active' : '' }}" 
                               href="{{ route('doctor.medical-records') }}">
                                <i class="fas fa-file-medical me-2"></i>Medical Records
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                    <div class="container-fluid">
                        <div class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-md me-1"></i>
                                    Dr. {{ Auth::guard('doctor')->user()->name }}
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

                <!-- Toast Notifications -->
                <div class="toast-container">
                    @foreach (['success', 'error', 'warning', 'info'] as $msg)
                        @if(session($msg))
                            <div class="toast align-items-center text-bg-{{ $msg == 'error' ? 'danger' : $msg }} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        {!! session($msg) !!}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'))
            toastElList.map(toastEl => new bootstrap.Toast(toastEl, { delay: 5000 })).forEach(toast => toast.show());
        });
    </script>
    @yield('scripts')
</body>
</html>
