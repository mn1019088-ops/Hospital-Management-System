@extends('layouts.doctor')

@section('title', 'Patient Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient Details</h1>
    <div>
        <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Patients
        </a>
        <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" class="btn btn-success">
            <i class="fas fa-file-medical me-2"></i>Add Medical Record
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $doctorId = Auth::guard('doctor')->id();
        $patientStats = [
            'total_visits' => $medicalRecords->count(),
            'appointments' => \App\Models\Appointment::where('patient_id', $patient->id)
                ->where('doctor_id', $doctorId)
                ->count(),
            'pending_appointments' => \App\Models\Appointment::where('patient_id', $patient->id)
                ->where('doctor_id', $doctorId)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
            'last_visit' => $medicalRecords->count() > 0 ? $medicalRecords->first()->visit_date->diffForHumans() : 'Never'
        ];
    @endphp

    @foreach([
        'total_visits' => ['Total Visits', 'primary', 'fas fa-calendar-check'],
        'appointments' => ['All Appointments', 'info', 'fas fa-list'],
        'pending_appointments' => ['Pending Appointments', 'warning', 'fas fa-clock'],
        'last_visit' => ['Last Visit', 'success', 'fas fa-history']
    ] as $key => [$title, $color, $icon])
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-{{ $color }} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                            {{ $title }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if($key === 'last_visit')
                                <small>{{ $patientStats[$key] }}</small>
                            @else
                                {{ $patientStats[$key] }}
                            @endif
                        </div>
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

<div class="row">
    <!-- Patient Information -->
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Patient Information
                </h5>
            </div>
            <div class="card-body text-center">
                <h4>{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                <p class="text-muted">ID: {{ $patient->patient_id }}</p>

                <div class="list-group list-group-flush text-start mt-3">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-birthday-cake text-primary me-2"></i>Age</span>
                        <strong>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' years' : 'N/A' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-venus-mars text-info me-2"></i>Gender</span>
                        <strong>{{ ucfirst($patient->gender) }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tint text-danger me-2"></i>Blood Group</span>
                        <strong>
                            @if($patient->blood_group)
                                <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-phone text-success me-2"></i>Phone</span>
                        <strong>{{ $patient->phone ?? 'N/A' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope text-warning me-2"></i>Email</span>
                        <strong>{{ $patient->email ?? 'N/A' }}</strong>
                    </div>
                    <div class="list-group-item">
                        <span><i class="fas fa-map-marker-alt text-secondary me-2"></i>Address</span>
                        <div class="mt-1">
                            <small class="text-muted">{{ $patient->address ?? 'Not provided' }}</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-plus text-success me-2"></i>Member Since</span>
                        <strong>{{ $patient->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-circle text-{{ $patient->is_active ? 'success' : 'danger' }} me-2"></i>Status</span>
                        <span class="badge bg-{{ $patient->is_active ? 'success' : 'danger' }}">
                            {{ $patient->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4 border-success shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-file-medical me-2"></i>New Medical Record
                    </a>
                    <a href="{{ route('doctor.patients.medical-records', $patient->id) }}" 
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-history me-2"></i>View All Records
                    </a>
                    <a href="{{ route('doctor.appointments') }}?patient_id={{ $patient->id }}" 
                       class="btn btn-info btn-sm">
                        <i class="fas fa-calendar me-2"></i>View Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Records & Additional Information -->
    <div class="col-md-8">
        <!-- Medical Records -->
        <div class="card border-info shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-medical me-2"></i>Medical Records
                </h5>
                <span class="badge bg-primary">{{ $medicalRecords->count() }} Records</span>
            </div>
            <div class="card-body p-0">
                @if($medicalRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Doctor</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalRecords as $record)
                            <tr>
                                <td>
                                    <div class="text-nowrap">
                                        <strong>{{ $record->visit_date->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ $record->visit_date->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $record->diagnosis }}">
                                        {{ \Illuminate\Support\Str::limit($record->diagnosis, 40) }}
                                    </span>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $record->treatment }}">
                                        {{ \Illuminate\Support\Str::limit($record->treatment, 40) }}
                                    </span>
                                </td>
                                <td>
                                    <small>Dr. {{ $record->doctor->name ?? 'N/A' }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('doctor.medical-records.show', $record->id) }}" 
                                           class="btn btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('doctor.medical-records.edit', $record->id) }}" 
                                           class="btn btn-warning" title="Edit Record">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Medical Records</h5>
                    <p class="text-muted">No medical records found for this patient.</p>
                    <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" 
                       class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create First Record
                    </a>
                </div>
                @endif
            </div>
            @if($medicalRecords->count() > 0)
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Showing {{ $medicalRecords->count() }} medical records
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('doctor.patients.medical-records', $patient->id) }}" 
                           class="btn btn-sm btn-outline-info">
                            View All Records <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Patient Medical History & Notes -->
        <div class="row mt-4">
            @if($patient->medical_history)
            <div class="col-md-6">
                <div class="card border-warning shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>Medical History
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $patient->medical_history }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($patient->allergies)
            <div class="col-md-6">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-allergies me-2"></i>Allergies
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $patient->allergies }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Recent Appointments -->
        @php
            $recentAppointments = \App\Models\Appointment::with('doctor')
                ->where('patient_id', $patient->id)
                ->where('doctor_id', $doctorId)
                ->latest()
                ->take(3)
                ->get();
        @endphp

        @if($recentAppointments->count() > 0)
        <div class="card mt-4 border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Recent Appointments
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentAppointments as $appointment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $appointment->appointment_date->format('M d, Y') }}</h6>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} • 
                                {{ ucfirst($appointment->appointment_type) }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .border-left-primary { 
        border-left: 0.25rem solid #4e73df !important; 
        }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important; 
        }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important; 
        }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important; 
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