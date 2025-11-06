@extends('layouts.patient')

@section('title', 'My Appointments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Appointments</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Appointment
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalAppointments = $appointments->total();
            $scheduledAppointments = $appointments->where('status', 'scheduled')->count();
            $confirmedAppointments = $appointments->where('status', 'confirmed')->count();
            $completedAppointments = $appointments->where('status', 'completed')->count();
            $cancelledAppointments = $appointments->where('status', 'cancelled')->count();
        @endphp

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Scheduled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $scheduledAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Confirmed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $confirmedAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Cancelled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelledAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Appointment List
                <span class="badge bg-light text-primary ms-2">{{ $appointments->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Appointment ID</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Date & Time</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Reason</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $appointment->appointment_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold">Dr. {{ $appointment->doctor->name }}</div>
                                        <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
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
                                @endphp
                                <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $appointment->reason }}">
                                    {{ $appointment->reason }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#appointmentModal{{ $appointment->id }}" 
                                            title="View Details" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button (only for scheduled/confirmed appointments) -->
                                    @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                    <a href="{{ route('patient.appointments.edit', $appointment) }}" 
                                       class="btn btn-warning" title="Edit Appointment" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    <!-- Cancel Button (only for scheduled/confirmed appointments) -->
                                    @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                    <button class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal{{ $appointment->id }}"
                                            title="Cancel Appointment" data-bs-toggle="tooltip">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>

                                <!-- View Appointment Modal -->
                                <div class="modal fade" id="appointmentModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-calendar-alt me-2"></i>Appointment Details
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="border-bottom pb-2">Appointment Information</h6>
                                                        <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id }}</p>
                                                        <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                        <p><strong>Status:</strong> 
                                                            <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                                {{ ucfirst($appointment->status) }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="border-bottom pb-2">Doctor Information</h6>
                                                        <p><strong>Name:</strong> Dr. {{ $appointment->doctor->name }}</p>
                                                        <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization }}</p>
                                                        <p><strong>Experience:</strong> {{ $appointment->doctor->years_of_experience ?? 'N/A' }} years</p>
                                                        <p><strong>Created:</strong> {{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6 class="border-bottom pb-2">Reason for Appointment</h6>
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <p class="mb-0">{{ $appointment->reason }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($appointment->notes)
                                                <div class="row mt-3">
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
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Close
                                                </button>
                                                @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                                <a href="{{ route('patient.appointments.edit', $appointment) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit me-1"></i>Edit Appointment
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cancel Confirmation Modal -->
                                @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1">
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
                                                    <strong>Note:</strong> Cancelling an appointment may be subject to cancellation policies. 
                                                    Please contact the clinic if you have any questions.
                                                </div>
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <strong>Appointment Details:</strong><br>
                                                        Doctor: Dr. {{ $appointment->doctor->name }}<br>
                                                        Date: {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                                        Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}<br>
                                                        Reason: {{ Str::limit($appointment->reason, 50) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Keep Appointment
                                                </button>
                                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-times me-1"></i>Yes, Cancel Appointment
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} entries
                </div>
                <div>
                    {{ $appointments->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Appointments Found</h4>
                <p class="text-muted">You haven't scheduled any appointments yet.</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Schedule Your First Appointment
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
        padding: 1rem 0.75rem;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

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
@endsection