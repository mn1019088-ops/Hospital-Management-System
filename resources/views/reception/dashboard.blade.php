@extends('layouts.reception')

@section('title', 'Reception Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reception Dashboard</h1>
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
    <div class="row mb-4">
        @php
            $cards = [
                ['title' => "Today's Appointments", 'value' => $stats['today_appointments'] ?? 0, 'icon' => 'fa-calendar-day', 'color' => 'primary', 'route' => 'reception.appointments'],
                ['title' => 'Total Patients', 'value' => $stats['total_patients'] ?? 0, 'icon' => 'fa-procedures', 'color' => 'info', 'route' => 'reception.patients.list'],
                ['title' => 'New Patients Today', 'value' => $stats['new_patients_today'] ?? 0, 'icon' => 'fa-user-plus', 'color' => 'success', 'route' => 'reception.patients.list'],
                ['title' => 'Available Doctors', 'value' => $stats['doctors_available'] ?? 0, 'icon' => 'fa-user-md', 'color' => 'warning', 'route' => 'reception.doctors'],
                ['title' => 'Pending Appointments', 'value' => $stats['pending_appointments'] ?? 0, 'icon' => 'fa-clock', 'color' => 'danger', 'route' => 'reception.appointments'],
                ['title' => 'Confirmed Appointments', 'value' => $stats['confirmed_appointments'] ?? 0, 'icon' => 'fa-check-circle', 'color' => 'success', 'route' => 'reception.appointments'],
                ['title' => 'Patients This Week', 'value' => $stats['patients_this_week'] ?? 0, 'icon' => 'fa-users', 'color' => 'info', 'route' => 'reception.patients.list'],
                ['title' => 'Appointments This Week', 'value' => $stats['appointments_this_week'] ?? 0, 'icon' => 'fa-calendar-alt', 'color' => 'primary', 'route' => 'reception.appointments'],
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
        <!-- Today's Appointments -->
        <div class="col-lg-8 mb-4">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Appointments
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-primary me-2">{{ $todayAppointments->count() }} total</span>
                        <a href="{{ route('reception.appointments') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($todayAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Time</th>
                                    <th class="border-0">Patient</th>
                                    <th class="border-0">Doctor</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                @php
                                    $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_time);
                                    $isUpcoming = $appointmentTime->isFuture();
                                    $isCurrent = $appointmentTime->diffInMinutes(now()) <= 30;
                                @endphp
                                <tr class="{{ $isCurrent ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-{{ $isUpcoming ? 'success' : 'secondary' }} me-2"></i>
                                            <div>
                                                <strong>{{ $appointmentTime->format('h:i A') }}</strong>
                                                @if($isCurrent)
                                                <br><small class="text-warning">Happening now</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                    </td>
                                    <td>
                                        <strong>Dr. {{ $appointment->doctor->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">{{ $appointment->appointment_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($appointment->status == 'scheduled') bg-warning text-dark
                                            @elseif($appointment->status == 'confirmed') bg-primary
                                            @elseif($appointment->status == 'completed') bg-success
                                            @else bg-secondary @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if($appointment->status == 'scheduled')
                                            <!-- FIXED: Using URL helper instead of route -->
                                            <form action="{{ url('/reception/appointments/' . $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Confirm Appointment"
                                                        onclick="return confirm('Confirm this appointment?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <a href="#" class="btn btn-info btn-sm" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#appointmentModal{{ $appointment->id }}"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Appointment Details Modal -->
                                <div class="modal fade" id="appointmentModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Appointment Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <strong>Patient:</strong><br>
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Doctor:</strong><br>
                                                        Dr. {{ $appointment->doctor->name }}
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <strong>Time:</strong><br>
                                                        {{ $appointmentTime->format('h:i A') }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Type:</strong><br>
                                                        {{ ucfirst($appointment->appointment_type) }}
                                                    </div>
                                                </div>
                                                @if($appointment->reason)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <strong>Reason:</strong><br>
                                                        {{ $appointment->reason }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Appointments Today</h5>
                        <p class="text-muted">There are no appointments scheduled for today.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">{{ $stats['completed_appointments_today'] ?? 0 }}</h5>
                                <small class="text-muted">Completed Today</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-bed fa-2x text-info mb-2"></i>
                                <h5 class="mb-1">{{ $stats['occupied_rooms'] ?? 0 }}</h5>
                                <small class="text-muted">Rooms Occupied</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">₹{{ number_format($stats['revenue_today'] ?? 0, 2) }}</h5>
                                <small class="text-muted">Today's Revenue</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h5 class="mb-1">{{ $stats['waiting_patients'] ?? 0 }}</h5>
                                <small class="text-muted">Waiting Now</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
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

    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
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

        // Update time every minute
        function updateTime() {
            const timeElement = document.querySelector('.btn-outline-primary');
            if (timeElement) {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit',
                    hour12: true 
                });
                timeElement.innerHTML = `<i class="fas fa-clock me-1"></i>${timeString}`;
            }
        }

        // Update time immediately and then every minute
        updateTime();
        setInterval(updateTime, 60000);
    });
</script>
@endsection