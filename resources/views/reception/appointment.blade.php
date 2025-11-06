@extends('layouts.reception')

@section('title', 'Appointment Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Appointment Management</h1>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
            <i class="fas fa-plus me-2"></i>Schedule Appointment
        </button>
    </div>

    <!-- Toastr Notifications -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalAppointments = $appointments->total();
            $scheduledAppointments = $appointments->where('status', 'scheduled')->count();
            $confirmedAppointments = $appointments->where('status', 'confirmed')->count();
            $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Appointments
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

        <div class="col-xl-3 col-md-6 mb-3">
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

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
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

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Appointments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Card -->
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
                <!-- FIXED: Changed route from list to the actual route name -->
                <form action="{{ route('reception.appointments') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by patient name..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ request('date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <!-- FIXED: Changed route from list to the actual route name -->
                            <a href="{{ route('reception.appointments') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Appointments Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
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
                            <th class="border-0">ID</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Date & Time</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Fee</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td><strong>#{{ $appointment->id }}</strong></td>
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
                                <strong>Dr. {{ $appointment->doctor->name }}</strong><br>
                                <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-capitalize">{{ $appointment->appointment_type }}</span>
                            </td>
                            <td>
                                <strong>₹{{ number_format($appointment->fee, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($appointment->status == 'completed') bg-success
                                    @elseif($appointment->status == 'confirmed') bg-primary
                                    @elseif($appointment->status == 'cancelled') bg-danger
                                    @else bg-warning @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal{{ $appointment->id }}" 
                                            title="View Details" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Confirm Button (only for scheduled appointments) -->
                                    @if($appointment->status == 'scheduled')
                                    <!-- FIXED: Using URL helper instead of route for update -->
                                    <form action="{{ url('/reception/appointments/' . $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-success" 
                                                title="Confirm Appointment"
                                                onclick="return confirm('Are you sure you want to confirm this appointment?')"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Complete Button (only for confirmed appointments) -->
                                    @if($appointment->status == 'confirmed')
                                    <!-- FIXED: Using URL helper instead of route for update -->
                                    <form action="{{ url('/reception/appointments/' . $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-primary" 
                                                title="Mark as Completed"
                                                onclick="return confirm('Are you sure you want to mark this appointment as completed?')"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Cancel Button (only for scheduled/confirmed appointments) -->
                                    @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                    <!-- FIXED: Using URL helper instead of route for destroy -->
                                    <form action="{{ url('/reception/appointments/' . $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                title="Cancel Appointment"
                                                onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- View Appointment Modal -->
                        <div class="modal fade" id="viewAppointmentModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-calendar-alt me-2"></i>Appointment Details - #{{ $appointment->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Patient Information</h6>
                                                <p><strong>Name:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                                <p><strong>Phone:</strong> {{ $appointment->patient->phone }}</p>
                                                <p><strong>Email:</strong> {{ $appointment->patient->email ?? 'N/A' }}</p>
                                                <p><strong>Age:</strong> {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . ' years' : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Doctor Information</h6>
                                                <p><strong>Name:</strong> Dr. {{ $appointment->doctor->name }}</p>
                                                <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization }}</p>
                                                <p><strong>Email:</strong> {{ $appointment->doctor->email }}</p>
                                                <p><strong>Phone:</strong> {{ $appointment->doctor->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Appointment Details</h6>
                                                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                                                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                <p><strong>Type:</strong> <span class="badge bg-info text-capitalize">{{ $appointment->appointment_type }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Payment & Status</h6>
                                                <p><strong>Fee:</strong> ₹{{ number_format($appointment->fee, 2) }}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge 
                                                        @if($appointment->status == 'completed') bg-success
                                                        @elseif($appointment->status == 'confirmed') bg-primary
                                                        @elseif($appointment->status == 'cancelled') bg-danger
                                                        @else bg-warning @endif">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </p>
                                                <p><strong>Created:</strong> {{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <h6 class="border-bottom pb-2">Reason for Visit</h6>
                                            <p class="mb-0">{{ $appointment->reason ?? 'No reason provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <p class="text-muted">No appointments match your search criteria.</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                    <i class="fas fa-plus me-1"></i>Schedule New Appointment
                </button>
                <!-- FIXED: Added clear filters button for empty state -->
                @if(request()->anyFilled(['search', 'doctor_id', 'status', 'date']))
                <a href="{{ route('reception.appointments') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Appointment Modal -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-primary">
            <!-- FIXED: Using URL helper instead of route for store -->
            <form action="{{ url('/reception/appointments') }}" method="POST" id="appointmentForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Schedule New Appointment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Select Patient</label>
                                <select class="form-control @error('patient_id') is-invalid @enderror" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->phone }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Select Doctor</label>
                                <select class="form-control @error('doctor_id') is-invalid @enderror" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Appointment Date</label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       name="appointment_date" id="appointment_date" 
                                       min="{{ date('Y-m-d') }}" 
                                       value="{{ old('appointment_date') }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Appointment Time</label>
                                <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                       name="appointment_time" id="appointment_time" 
                                       value="{{ old('appointment_time') }}" required>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Appointment Type</label>
                                <select class="form-control @error('appointment_type') is-invalid @enderror" name="appointment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="checkup" {{ old('appointment_type') == 'checkup' ? 'selected' : '' }}>Checkup</option>
                                    <option value="surgery" {{ old('appointment_type') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                                    <option value="follow-up" {{ old('appointment_type') == 'follow-up' ? 'selected' : '' }}>Follow-up</option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('appointment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Consultation Fee (₹)</label>
                                <input type="number" class="form-control @error('fee') is-invalid @enderror" 
                                       name="fee" step="0.01" min="0" 
                                       value="{{ old('fee', '500') }}" required>
                                @error('fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Reason for Visit</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="3" placeholder="Describe the reason for appointment..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }

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

        // Appointment form validation
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                const appointmentDate = document.getElementById('appointment_date').value;
                const appointmentTime = document.getElementById('appointment_time').value;
                const currentDate = new Date().toISOString().split('T')[0];
                const currentTime = new Date().toTimeString().split(' ')[0].substring(0, 5);

                if (appointmentDate === currentDate && appointmentTime <= currentTime) {
                    e.preventDefault();
                    alert('Cannot schedule appointment for current or past time. Please select a future time.');
                    return false;
                }
            });

            // Real-time validation
            document.getElementById('appointment_date').addEventListener('change', validateDateTime);
            document.getElementById('appointment_time').addEventListener('change', validateDateTime);

            function validateDateTime() {
                const appointmentDate = document.getElementById('appointment_date').value;
                const appointmentTime = document.getElementById('appointment_time').value;
                const currentDate = new Date().toISOString().split('T')[0];
                const currentTime = new Date().toTimeString().split(' ')[0].substring(0, 5);

                if (appointmentDate === currentDate && appointmentTime <= currentTime) {
                    alert('Please select a future time for today\'s appointment.');
                    document.getElementById('appointment_time').focus();
                }
            }
        }

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }
    });
</script>
@endsection