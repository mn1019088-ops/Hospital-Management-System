@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Dashboard</h1>
    <div class="btn-group me-2">
        <span class="btn btn-sm btn-primary">
            <i class="fas fa-calendar me-1"></i>{{ now()->format('F d, Y') }}
        </span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    @php
        $doctorId = Auth::guard('doctor')->id();
        $today = \Carbon\Carbon::today();
        
        // Calculate statistics
        $stats = [
            'today_appointments' => \App\Models\Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $today)
                ->count(),
            'total_patients' => \App\Models\Patient::whereHas('appointments', function($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })->count(),
            'pending_consultations' => \App\Models\Appointment::where('doctor_id', $doctorId)
                ->where('status', 'scheduled')
                ->count(),
            'completed_today' => \App\Models\Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $today)
                ->where('status', 'completed')
                ->count(),
            'total_appointments' => \App\Models\Appointment::where('doctor_id', $doctorId)->count(),
            'confirmed_appointments' => \App\Models\Appointment::where('doctor_id', $doctorId)
                ->where('status', 'confirmed')
                ->count(),
        ];
    @endphp

    @foreach([
        'today_appointments' => ["Today's Appointments", 'primary', 'fas fa-calendar-day', $stats['today_appointments']],
        'total_patients' => ['Total Patients', 'success', 'fas fa-procedures', $stats['total_patients']],
        'pending_consultations' => ['Pending Consultations', 'warning', 'fas fa-clock', $stats['pending_consultations']],
        'completed_today' => ['Completed Today', 'info', 'fas fa-check-circle', $stats['completed_today']],
        'confirmed_appointments' => ['Confirmed', 'secondary', 'fas fa-check-double', $stats['confirmed_appointments']],
        'total_appointments' => ['Total Appointments', 'dark', 'fas fa-list', $stats['total_appointments']]
    ] as $key => [$title, $color, $icon, $count])
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-{{ $color }} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                            {{ $title }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $count }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="{{ $icon }} fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Today's Schedule -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-light shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Today's Schedule
                    <small class="ms-2">{{ $today->format('F d, Y') }}</small>
                </h5>
                <span class="badge bg-light text-primary">{{ $todaySchedule->count() }} Appointments</span>
            </div>
            <div class="card-body p-0">
                @if($todaySchedule->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Time</th>
                                <th class="border-0">Patient Information</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Reason</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaySchedule as $appointment)
                            <tr class="{{ $appointment->status == 'in-progress' ? 'table-info' : '' }}">
                                <td>
                                    <div class="text-nowrap">
                                        <strong class="d-block">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</strong>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->diffForHumans() }}</small>
                                    </div>
                                </td>
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
                                            <h6 class="mb-0">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                            <small class="text-muted">
                                                ID: {{ $appointment->patient->patient_id }}<br>
                                                Age: {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . 'y' : 'N/A' }} | 
                                                {{ ucfirst($appointment->patient->gender) }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark text-uppercase">
                                        {{ $appointment->appointment_type }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'warning',
                                            'confirmed' => 'primary',
                                            'in-progress' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusIcons = [
                                            'scheduled' => 'clock',
                                            'confirmed' => 'check-circle',
                                            'in-progress' => 'spinner',
                                            'completed' => 'check',
                                            'cancelled' => 'times-circle'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $statusIcons[$appointment->status] ?? 'circle' }} me-1"></i>
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                          data-bs-toggle="tooltip" title="{{ $appointment->reason }}">
                                        {{ \Illuminate\Support\Str::limit($appointment->reason, 30) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Start Appointment Button -->
                                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                            <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="in-progress">
                                                <button type="submit" class="btn btn-info text-white" 
                                                        title="Start Appointment"
                                                        onclick="return confirm('Start appointment with {{ $appointment->patient->first_name }}?')">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Complete Appointment Button -->
                                        @if($appointment->status === 'in-progress')
                                            <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-success" 
                                                        title="Complete Appointment"
                                                        onclick="return confirm('Mark appointment with {{ $appointment->patient->first_name }} as completed?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- View Details Button -->
                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}" 
                                           class="btn btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('doctor.appointments.edit', $appointment->id) }}" 
                                           class="btn btn-warning" title="Edit Appointment">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($appointment->status === 'completed')
                                            <span class="btn btn-outline-success" title="Completed">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Appointments Today</h4>
                    <p class="text-muted">You have no appointments scheduled for today.</p>
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-alt me-1"></i>View All Appointments
                    </a>
                </div>
                @endif
            </div>
            @if($todaySchedule->count() > 0)
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Click on actions to manage appointments
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-outline-primary">
                            View All Appointments <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Activity -->
@php
    $recentAppointments = \App\Models\Appointment::with('patient')
        ->where('doctor_id', $doctorId)
        ->latest()
        ->take(5)
        ->get();
@endphp

@if($recentAppointments->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-light shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Recent Appointments
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentAppointments as $appointment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                            <small class="text-muted">
                                {{ $appointment->appointment_date->format('M d, Y') }} at 
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} • 
                                {{ ucfirst($appointment->appointment_type) }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    .border-left-dark {
        border-left: 0.25rem solid #5a5c69 !important;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            window.location.reload();
        }, 300000); // 5 minutes

        // Add confirmation for status changes
        const statusForms = document.querySelectorAll('form[action*="update-status"]');
        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const status = this.querySelector('input[name="status"]').value;
                const patientName = this.closest('tr').querySelector('h6').textContent.trim();
                
                let message = '';
                switch(status) {
                    case 'in-progress':
                        message = `Start appointment with ${patientName}?`;
                        break;
                    case 'completed':
                        message = `Mark appointment with ${patientName} as completed?`;
                        break;
                }
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection