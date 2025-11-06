@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <span class="btn btn-sm btn-primary">
                    <i class="fas fa-calendar me-1"></i>{{ now()->format('F d, Y') }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        @php
            $cards = [
                [
                    'title' => 'Total Doctors',
                    'value' => $stats['total_doctors'] ?? 0,
                    'icon' => 'fa-user-md',
                    'color' => 'primary',
                    'route' => 'admin.doctors',
                    'trend' => $stats['doctor_growth'] ?? 0
                ],
                [
                    'title' => 'Total Patients',
                    'value' => $stats['total_patients'] ?? 0,
                    'icon' => 'fa-procedures',
                    'color' => 'success',
                    'route' => 'admin.patients',
                    'trend' => $stats['patient_growth'] ?? 0
                ],
                [
                    'title' => "Today's Appointments",
                    'value' => $stats['today_appointments'] ?? 0,
                    'icon' => 'fa-calendar-check',
                    'color' => 'warning',
                    'route' => 'admin.appointments',
                    'trend' => null
                ],
                [
                    'title' => 'Available Rooms',
                    'value' => $stats['available_rooms'] ?? 0,
                    'icon' => 'fa-bed',
                    'color' => 'info',
                    'route' => 'admin.rooms',
                    'trend' => null
                ],
                [
                    'title' => 'Active Staff',
                    'value' => $stats['active_staff'] ?? 0,
                    'icon' => 'fa-user-nurse',
                    'color' => 'danger',
                    'route' => 'admin.staff',
                    'trend' => null
                ],
                [
                    'title' => 'Monthly Revenue',
                    'value' => '₹' . number_format($stats['monthly_revenue'] ?? 0, 2),
                    'icon' => 'fa-money-bill-wave',
                    'color' => 'success',
                    'route' => 'admin.reports',
                    'trend' => $stats['revenue_growth'] ?? 0
                ],
                [
                    'title' => 'Departments',
                    'value' => $stats['total_departments'] ?? 0,
                    'icon' => 'fa-building',
                    'color' => 'secondary',
                    'route' => 'admin.departments',
                    'trend' => null
                ],
                [
                    'title' => 'Pending Tasks',
                    'value' => $stats['pending_tasks'] ?? 0,
                    'icon' => 'fa-tasks',
                    'color' => 'warning',
                    'route' => 'admin.tasks',
                    'trend' => null
                ]
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                <a href="{{ route($card['route']) }}" class="text-decoration-none text-{{ $card['color'] }}">
                                    {{ $card['title'] }}
                                </a>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['value'] }}</div>
                            @if($card['trend'] !== null)
                            <div class="mt-2 mb-0 text-xs">
                                @if($card['trend'] > 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>{{ abs($card['trend']) }}%
                                </span>
                                @elseif($card['trend'] < 0)
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down me-1"></i>{{ abs($card['trend']) }}%
                                </span>
                                @else
                                <span class="text-muted">
                                    <i class="fas fa-minus me-1"></i>0%
                                </span>
                                @endif
                                <span class="text-muted">from last month</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas {{ $card['icon'] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Appointments -->
        <div class="col-lg-8 mb-4">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Recent Appointments
                    </h5>
                    <a href="{{ route('admin.appointments') }}" class="btn btn-sm btn-light">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Patient</th>
                                    <th class="border-0">Doctor</th>
                                    <th class="border-0">Date & Time</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAppointments as $appointment)
                                <tr>
                                    <td><strong>#{{ $appointment->appointment_id ?? $appointment->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if($appointment->patient->profile_image)
                                                    <img src="{{ asset('storage/' . $appointment->patient->profile_image) }}" 
                                                         alt="{{ $appointment->patient->first_name }}" 
                                                         class="rounded-circle avatar-sm">
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                                <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Dr. {{ $appointment->doctor->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                    </td>
                                    <td>
                                        <div class="text-nowrap">
                                            <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">{{ $appointment->appointment_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($appointment->status == 'scheduled') bg-warning text-dark
                                            @elseif($appointment->status == 'confirmed') bg-primary
                                            @elseif($appointment->status == 'completed') bg-success
                                            @elseif($appointment->status == 'cancelled') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($appointment->fee, 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Recent Appointments</h5>
                        <p class="text-muted">No appointments have been scheduled recently.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card border-success shadow-sm mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach([
                            ['title' => 'Pending Appointments', 'value' => $stats['pending_appointments'] ?? 0, 'color' => 'warning', 'icon' => 'fa-clock'],
                            ['title' => 'Active Doctors', 'value' => $stats['active_doctors'] ?? 0, 'color' => 'primary', 'icon' => 'fa-user-md'],
                            ['title' => 'Occupied Rooms', 'value' => $stats['occupied_rooms'] ?? 0, 'color' => 'danger', 'icon' => 'fa-bed'],
                            ['title' => "Today's Revenue", 'value' => '₹' . number_format($stats['today_revenue'] ?? 0, 2), 'color' => 'success', 'icon' => 'fa-money-bill-wave'],
                            ['title' => 'New Patients Today', 'value' => $stats['new_patients_today'] ?? 0, 'color' => 'info', 'icon' => 'fa-user-plus'],
                            ['title' => 'Completed Today', 'value' => $stats['completed_appointments_today'] ?? 0, 'color' => 'success', 'icon' => 'fa-check-circle']
                        ] as $stat)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }} me-3"></i>
                                <span>{{ $stat['title'] }}</span>
                            </div>
                            <span class="badge bg-{{ $stat['color'] }} rounded-pill">
                                {{ $stat['value'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 40px;
        height: 40px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }

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

    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }

    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }

    .card-header {
        font-weight: 600;
        border-bottom: 1px solid #e3e6f0;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
        padding: 1rem 0.75rem;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .list-group-item {
        border: none;
        padding: 1rem 1.25rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection