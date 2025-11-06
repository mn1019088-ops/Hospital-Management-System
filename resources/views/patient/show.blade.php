@extends('layouts.app')

@section('title', $patient->full_name . ' - Patient Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Patient Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Patients
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Patient Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar-placeholder bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                    <h4 class="card-title">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                    <p class="text-muted">{{ $patient->patient_id }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }} me-2">
                            {{ $patient->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($patient->blood_group)
                        <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                        @endif
                    </div>

                    <div class="list-group list-group-flush text-start">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-envelope me-2 text-muted"></i>Email</span>
                            <span>{{ $patient->email ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-phone me-2 text-muted"></i>Phone</span>
                            <span>{{ $patient->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-birthday-cake me-2 text-muted"></i>Age</span>
                            <span>{{ $patient->age ?? 'N/A' }} years</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-venus-mars me-2 text-muted"></i>Gender</span>
                            <span class="text-capitalize">{{ $patient->gender }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('patients.appointments.create', $patient) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Create Appointment
                        </a>
                        <a href="{{ route('patients.medical-records.create', $patient) }}" class="btn btn-success">
                            <i class="fas fa-file-medical me-2"></i>Add Medical Record
                        </a>
                        <a href="{{ route('patients.appointments', $patient) }}" class="btn btn-info">
                            <i class="fas fa-list me-2"></i>View Appointments
                        </a>
                        <a href="{{ route('patients.medical-records', $patient) }}" class="btn btn-warning">
                            <i class="fas fa-file-alt me-2"></i>View Medical Records
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Patient ID:</th>
                                    <td>{{ $patient->patient_id }}</td>
                                </tr>
                                <tr>
                                    <th>First Name:</th>
                                    <td>{{ $patient->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>Last Name:</th>
                                    <td>{{ $patient->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Gender:</th>
                                    <td class="text-capitalize">{{ $patient->gender }}</td>
                                </tr>
                                <tr>
                                    <th>Blood Group:</th>
                                    <td>
                                        @if($patient->blood_group)
                                            <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }}">
                                            {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Member Since:</th>
                                    <td>{{ $patient->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($patient->address)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Address</h6>
                            <p class="text-muted">{{ $patient->address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Medical Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2"></i>Medical Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Medical History</h6>
                            <p class="text-muted">{{ $patient->medical_history ?: 'No medical history recorded' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Allergies</h6>
                            <p class="text-muted">{{ $patient->allergies ?: 'No allergies recorded' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>Recent Appointments
                    </h5>
                    <a href="{{ route('patients.appointments', $patient) }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                    <td>Dr. {{ $appointment->doctor->name }}</td>
                                    <td>{{ Str::limit($appointment->reason, 30) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'warning',
                                                'confirmed' => 'primary',
                                                'in-progress' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No appointments found</p>
                        <a href="{{ route('patients.appointments.create', $patient) }}" class="btn btn-sm btn-primary mt-2">
                            Schedule Appointment
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Medical Records -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Recent Medical Records
                    </h5>
                    <a href="{{ route('patients.medical-records', $patient) }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($medicalRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicalRecords as $record)
                                <tr>
                                    <td>{{ $record->record_date->format('M d, Y') }}</td>
                                    <td>Dr. {{ $record->doctor->name }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 30) }}</td>
                                    <td>{{ Str::limit($record->treatment, 30) ?: 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="fas fa-file-medical-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No medical records found</p>
                        <a href="{{ route('patients.medical-records.create', $patient) }}" class="btn btn-sm btn-success mt-2">
                            Add Medical Record
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.list-group-item {
    border: none;
    padding: 0.75rem 0;
}
</style>
@endsection