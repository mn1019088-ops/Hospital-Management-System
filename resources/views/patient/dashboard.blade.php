@extends('layouts.patient')

@section('title', 'Patient Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Patient Dashboard</h1>
        <div class="btn-group me-2">
            <span class="btn btn-sm btn-primary">
                <i class="fas fa-calendar me-1"></i>{{ now()->format('F d, Y') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        @php
            $patientId = Auth::guard('patient')->id();
            $today = \Carbon\Carbon::today();
            
            // Calculate statistics
            $stats = [
                'today_appointments' => \App\Models\Appointment::where('patient_id', $patientId)
                    ->whereDate('appointment_date', $today)
                    ->count(),
                'total_appointments' => \App\Models\Appointment::where('patient_id', $patientId)->count(),
                'upcoming_appointments' => \App\Models\Appointment::where('patient_id', $patientId)
                    ->where('appointment_date', '>=', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'medical_records' => \App\Models\MedicalRecord::where('patient_id', $patientId)->count(),
                'pending_results' => \App\Models\Appointment::where('patient_id', $patientId)
                    ->where('status', 'in-progress')
                    ->count(),
                'completed_visits' => \App\Models\Appointment::where('patient_id', $patientId)
                    ->where('status', 'completed')
                    ->count(),
            ];
        @endphp

        @foreach([
            'today_appointments' => ["Today's Appointments", 'primary', 'fas fa-calendar-day', $stats['today_appointments']],
            'upcoming_appointments' => ['Upcoming Appointments', 'success', 'fas fa-calendar-check', $stats['upcoming_appointments']],
            'medical_records' => ['Medical Records', 'info', 'fas fa-file-medical', $stats['medical_records']],
            'pending_results' => ['Pending Results', 'warning', 'fas fa-clock', $stats['pending_results']],
            'completed_visits' => ['Completed Visits', 'secondary', 'fas fa-check-circle', $stats['completed_visits']],
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

    <!-- Today's Appointments & Recent Activity -->
    <div class="row mt-4">
        <!-- Today's Appointments -->
        @php
            $todayAppointments = \App\Models\Appointment::with('doctor')
                ->where('patient_id', $patientId)
                ->whereDate('appointment_date', $today)
                ->orderBy('appointment_time')
                ->get();
        @endphp

        <div class="col-lg-6">
            <div class="card border-light shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Appointments
                    </h5>
                    <span class="badge bg-light text-info">{{ $todayAppointments->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($todayAppointments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($todayAppointments as $appointment)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Dr. {{ $appointment->doctor->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        {{ $appointment->doctor->specialization }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'warning',
                                            'confirmed' => 'primary',
                                            'in-progress' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }} mb-2">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $appointment->appointment_type }}</small>
                                </div>
                            </div>
                            @if($appointment->reason)
                            <div class="mt-2">
                                <small class="text-muted">
                                    <strong>Reason:</strong> {{ \Illuminate\Support\Str::limit($appointment->reason, 50) }}
                                </small>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Appointments Today</h6>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-sm btn-info mt-2">
                            Book an Appointment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        @php
            $upcomingAppointments = \App\Models\Appointment::with('doctor')
                ->where('patient_id', $patientId)
                ->where('appointment_date', '>', $today)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->take(5)
                ->get();
        @endphp

        <div class="col-lg-6">
            <div class="card border-light shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments
                    </h5>
                    <span class="badge bg-light text-success">{{ $upcomingAppointments->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($upcomingAppointments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingAppointments as $appointment)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Dr. {{ $appointment->doctor->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $appointment->appointment_date->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Upcoming Appointments</h6>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-sm btn-success mt-2">
                            Schedule New Appointment
                        </a>
                    </div>
                    @endif
                </div>
                @if($upcomingAppointments->count() > 0)
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-outline-success">
                        View All Appointments <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Medical Records -->
    @php
        $recentMedicalRecords = \App\Models\MedicalRecord::with('doctor')
            ->where('patient_id', $patientId)
            ->latest()
            ->take(3)
            ->get();
    @endphp

    @if($recentMedicalRecords->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-light shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Recent Medical Records
                    </h5>
                    <a href="{{ route('patient.medical-records.index') }}" class="btn btn-sm btn-light">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentMedicalRecords as $record)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $record->diagnosis }}</h6>
                                    <p class="card-text small text-muted">
                                        <strong>Doctor:</strong> Dr. {{ $record->doctor->name }}<br>
                                        <strong>Date:</strong> {{ $record->record_date->format('M d, Y') }}<br>
                                        @if($record->treatment)
                                        <strong>Treatment:</strong> {{ \Illuminate\Support\Str::limit($record->treatment, 50) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('patient.medical-records.show', $record) }}" class="btn btn-sm btn-outline-warning w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-secondary { border-left: 0.25rem solid #858796 !important; }
    .border-left-dark { border-left: 0.25rem solid #5a5c69 !important; }
    
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
    
    .quick-action-btn {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .quick-action-btn:hover {
        transform: translateY(-3px);
        border-color: var(--bs-primary);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e3e6f0;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh dashboard every 10 minutes
        setTimeout(function() {
            window.location.reload();
        }, 600000); // 10 minutes

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection