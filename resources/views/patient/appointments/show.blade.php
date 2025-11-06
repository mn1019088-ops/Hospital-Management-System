@extends('layouts.patient')

@section('title', 'Appointment Details - ' . $appointment->appointment_id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Appointment Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Back to Appointments
            </a>
            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                <a href="{{ route('patient.appointments.edit', $appointment->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-1"></i>Edit Appointment
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Appointment Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Appointment #{{ $appointment->appointment_id }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Appointment ID:</th>
                                    <td class="fw-bold">{{ $appointment->appointment_id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Doctor:</th>
                                    <td>Dr. {{ $appointment->doctor->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Specialization:</th>
                                    <td>{{ $appointment->doctor->specialization }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Appointment Date:</th>
                                    <td>{{ $appointment->appointment_date->format('l, M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Appointment Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Type:</th>
                                    <td>
                                        <span class="badge bg-info text-capitalize">
                                            {{ str_replace('-', ' ', $appointment->appointment_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status:</th>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'warning',
                                                'confirmed' => 'primary',
                                                'in-progress' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'no-show' => 'secondary'
                                            ];
                                            $statusIcons = [
                                                'scheduled' => 'clock',
                                                'confirmed' => 'check-circle',
                                                'in-progress' => 'spinner',
                                                'completed' => 'check-double',
                                                'cancelled' => 'times-circle',
                                                'no-show' => 'exclamation-circle'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                            <i class="fas fa-{{ $statusIcons[$appointment->status] ?? 'circle' }} me-1"></i>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Fee:</th>
                                    <td class="fw-bold text-success">₹{{ number_format($appointment->fee, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Appointment Timeline</h6>
                            <div class="timeline">
                                <div class="timeline-item {{ $appointment->created_at ? 'active' : '' }}">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Appointment Created</h6>
                                        <small class="text-muted">{{ $appointment->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                @if($appointment->confirmed_at)
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Appointment Confirmed</h6>
                                        <small class="text-muted">{{ $appointment->confirmed_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($appointment->completed_at)
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Appointment Completed</h6>
                                        <small class="text-muted">{{ $appointment->completed_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Reason for Appointment -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Reason for Appointment</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $appointment->reason ?? 'No reason provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    @if($appointment->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Additional Notes</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $appointment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Doctor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>Doctor Information
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-placeholder bg-primary rounded-circle mx-auto mb-3">
                        <i class="fas fa-user-md fa-2x text-white"></i>
                    </div>
                    <h5>Dr. {{ $appointment->doctor->name }}</h5>
                    <p class="text-muted mb-3">{{ $appointment->doctor->specialization }}</p>
                    
                    <div class="list-group list-group-flush text-start">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Email</small>
                            <div>{{ $appointment->doctor->email }}</div>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Phone</small>
                            <div>{{ $appointment->doctor->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Experience</small>
                            <div>{{ $appointment->doctor->years_of_experience ?? 'N/A' }} years</div>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Status</small>
                            <div>
                                <span class="badge bg-success">
                                    <i class="fas fa-circle me-1"></i>Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                            <a href="{{ route('patient.appointments.edit', $appointment->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit Appointment
                            </a>
                            
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelAppointmentModal">
                                <i class="fas fa-times me-2"></i>Cancel Appointment
                            </button>
                        @endif
                        
                        @if($appointment->status === 'completed')
                            <a href="{{ route('patient.medical-records.index') }}?appointment={{ $appointment->id }}" class="btn btn-info">
                                <i class="fas fa-file-medical me-2"></i>View Medical Record
                            </a>
                        @endif
                        
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-2"></i>Book New Appointment
                        </a>
                        
                        <a href="javascript:window.print()" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i>Print Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Days Until Appointment</small>
                            <span class="badge bg-{{ $appointment->appointment_date->isFuture() ? 'primary' : 'secondary' }}">
                                {{ $appointment->appointment_date->diffInDays(now()) }}
                            </span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Appointment Duration</small>
                            <span class="badge bg-info">30 mins</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Last Updated</small>
                            <small>{{ $appointment->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this appointment?</p>
                <div class="alert alert-warning">
                    <strong>Note:</strong> Cancelling an appointment may be subject to cancellation fees depending on the timing.
                </div>
                <div class="card bg-light">
                    <div class="card-body">
                        <strong>Appointment Details:</strong><br>
                        Doctor: Dr. {{ $appointment->doctor->name }}<br>
                        Date: {{ $appointment->appointment_date->format('M d, Y') }}<br>
                        Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Appointment</button>
                <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-times me-1"></i>Yes, Cancel Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.list-group-item {
    border: none;
    padding: 0.5rem 0;
}
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}
.timeline-item.active .timeline-marker {
    background: #28a745 !important;
}
.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #6c757d;
}
.timeline-content {
    padding-bottom: 1rem;
}
@media print {
    .btn, .modal, .card-header .float-end {
        display: none !important;
    }
}
</style>
@endsection