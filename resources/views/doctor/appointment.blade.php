@extends('layouts.doctor')

@section('title', 'My Appointments')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
        <h1 class="h2">My Appointments</h1>
        <div class="btn-group">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Appointments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->where('appointment_date', \Carbon\Carbon::today())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                Scheduled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->where('status', 'scheduled')->count() }}
                            </div>
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
                                Confirmed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->where('status', 'confirmed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->where('status', 'in-progress')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
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
                                Cancelled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->where('status', 'cancelled')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $appointments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Search & Filter
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter"></i> Toggle Filters
                    </button>
                </div>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('doctor.appointments') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Search by patient name, ID, or reason..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Apply</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-secondary">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Appointments Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>All Appointments
                <span class="badge bg-light text-primary ms-2">{{ $appointments->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }}</span>
            </div>
        </div>

        <div class="card-body">
            @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Appointment ID</th>
                            <th>Patient</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                                <td>
                                    <strong>{{ $appointment->appointment_id }}</strong>
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
                                            <h6 class="mb-0">
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                            </h6>
                                            <small class="text-muted">
                                                ID: {{ $appointment->patient->patient_id }} | 
                                                Age: {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . 'y' : 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark text-uppercase">
                                        {{ $appointment->appointment_type }}
                                    </span>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $appointment->reason }}">
                                        {{ \Illuminate\Support\Str::limit($appointment->reason, 30) }}
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
                                    <div class="btn-group" role="group">
                                        <!-- Start Appointment Button -->
                                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                            <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="in-progress">
                                                <button type="submit" class="btn btn-sm btn-info" 
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
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Complete Appointment"
                                                        onclick="return confirm('Mark appointment as completed?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- View Details Button -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#viewAppointmentModal{{ $appointment->id }}" 
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <a href="{{ route('doctor.appointments.edit', $appointment->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit Appointment">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Cancel Button -->
                                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                            <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        title="Cancel Appointment"
                                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- View Appointment Modal -->
                            <div class="modal fade" id="viewAppointmentModal{{ $appointment->id }}" tabindex="-1"
                                 aria-labelledby="viewAppointmentModalLabel{{ $appointment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content border-light shadow-sm">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="viewAppointmentModalLabel{{ $appointment->id }}">
                                                <i class="fas fa-calendar-alt me-2"></i>Appointment Details
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-user me-2"></i>Patient Information
                                                    </h6>
                                                    <div class="mb-3">
                                                        <strong>Name:</strong><br>
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Patient ID:</strong><br>
                                                        {{ $appointment->patient->patient_id }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Age & Gender:</strong><br>
                                                        {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . ' years' : 'N/A' }}, 
                                                        {{ ucfirst($appointment->patient->gender) }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Phone:</strong><br>
                                                        {{ $appointment->patient->phone ?? 'N/A' }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Email:</strong><br>
                                                        {{ $appointment->patient->email ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-calendar me-2"></i>Appointment Details
                                                    </h6>
                                                    <div class="mb-3">
                                                        <strong>Appointment ID:</strong><br>
                                                        {{ $appointment->appointment_id }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Date:</strong><br>
                                                        {{ $appointment->appointment_date->format('F d, Y') }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Time:</strong><br>
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Type:</strong><br>
                                                        <span class="badge bg-info text-dark text-uppercase">
                                                            {{ $appointment->appointment_type }}
                                                        </span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Status:</strong><br>
                                                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                            <i class="fas fa-{{ $statusIcons[$appointment->status] ?? 'circle' }} me-1"></i>
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </div>
                                                    @if($appointment->fee)
                                                    <div class="mb-3">
                                                        <strong>Fee:</strong><br>
                                                        ${{ number_format($appointment->fee, 2) }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-stethoscope me-2"></i>Medical Information
                                                    </h6>
                                                    <div class="mb-3">
                                                        <strong>Reason for Visit:</strong><br>
                                                        {{ $appointment->reason }}
                                                    </div>
                                                    @if($appointment->notes)
                                                    <div class="mb-3">
                                                        <strong>Additional Notes:</strong><br>
                                                        {{ $appointment->notes }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Close
                                            </button>
                                            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                            <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="in-progress">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="fas fa-play me-1"></i>Start
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
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
                <p class="text-muted">No appointments match your search criteria.</p>
                <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
            </div>
            @endif
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
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        // Show filters if any search parameters are present
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }

        // Date field - set max date to one year from now
        const dateField = document.getElementById('date');
        if (dateField) {
            const today = new Date();
            const oneYearLater = new Date(today.getFullYear() + 1, today.getMonth(), today.getDate());
            dateField.max = oneYearLater.toISOString().split('T')[0];
        }

        // Status change confirmation
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
                    case 'cancelled':
                        message = `Cancel appointment with ${patientName}? This action cannot be undone.`;
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