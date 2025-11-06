@extends('layouts.admin')

@section('title', 'Appointment Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Appointment Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                <i class="fas fa-plus me-2"></i>Add New Appointment
            </button>
        </div>
    </div>

    <!-- Alerts -->
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalAppointments = $appointments->total();
            $pendingAppointments = \App\Models\Appointment::where('status', 'pending')->count();
            $confirmedAppointments = \App\Models\Appointment::where('status', 'confirmed')->count();
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
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingAppointments }}</div>
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
                <form action="{{ route('admin.appointments') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by patient, doctor, or ID..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointment_type" name="appointment_type">
                                <option value="">All Types</option>
                                <option value="consultation" {{ request('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="checkup" {{ request('appointment_type') == 'checkup' ? 'selected' : '' }}>Checkup</option>
                                <option value="surgery" {{ request('appointment_type') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                                <option value="followup" {{ request('appointment_type') == 'followup' ? 'selected' : '' }}>Follow-up</option>
                                <option value="emergency" {{ request('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
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
                            <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">
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
                            <th class="border-0">Appointment Details</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Date & Time</th>
                            <th class="border-0">Fee</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                <strong>#{{ $appointment->appointment_id ?? $appointment->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <span class="badge bg-info text-dark text-capitalize">{{ $appointment->appointment_type }}</span>
                                    @if($appointment->reason)
                                    <br>
                                    <small class="text-muted" title="{{ $appointment->reason }}">
                                        {{ Str::limit($appointment->reason, 30) }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong class="d-block">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                        <small class="text-muted">{{ $appointment->patient->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong class="d-block">Dr. {{ $appointment->doctor->name }}</strong>
                                        <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong class="d-block text-primary">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <strong class="text-success">${{ number_format($appointment->fee, 2) }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($appointment->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'confirmed' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal{{ $appointment->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAppointmentModal{{ $appointment->id }}" 
                                            title="Edit Appointment">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAppointmentModal{{ $appointment->id }}" 
                                            title="Delete Appointment">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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
                    {{ $appointments->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Appointments Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'status', 'appointment_type', 'date']))
                        No appointments match your search criteria.
                    @else
                        No appointments are currently scheduled.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'status', 'appointment_type', 'date']))
                <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                    <i class="fas fa-plus me-1"></i>Create First Appointment
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Appointment Modal -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Add New Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.appointments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                                <select class="form-select" id="patient_id" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select" id="doctor_id" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="appointment_time" name="appointment_time" 
                                       value="{{ old('appointment_time') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appointment_type" class="form-label">Appointment Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="appointment_type" name="appointment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="checkup" {{ old('appointment_type') == 'checkup' ? 'selected' : '' }}>Checkup</option>
                                    <option value="surgery" {{ old('appointment_type') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                                    <option value="followup" {{ old('appointment_type') == 'followup' ? 'selected' : '' }}>Follow-up</option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fee" class="form-label">Fee ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="fee" name="fee" 
                                       value="{{ old('fee') }}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Appointment</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" 
                                  placeholder="Brief reason for the appointment...">{{ old('reason') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" 
                                  placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Create Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals Section -->
@foreach($appointments as $appointment)
<!-- View Appointment Modal -->
<div class="modal fade" id="viewAppointmentModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Appointment Details - #{{ $appointment->appointment_id ?? $appointment->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Patient Information</h6>
                        <p><strong>Name:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        <p><strong>Phone:</strong> {{ $appointment->patient->phone ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($appointment->patient->gender) }}</p>
                        <p><strong>Age:</strong> 
                            {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . ' years' : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Doctor Information</h6>
                        <p><strong>Name:</strong> Dr. {{ $appointment->doctor->name }}</p>
                        <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization }}</p>
                        <p><strong>Email:</strong> {{ $appointment->doctor->email }}</p>
                        <p><strong>Phone:</strong> {{ $appointment->doctor->phone ?? 'N/A' }}</p>
                        <p><strong>Experience:</strong> {{ $appointment->doctor->experience_years ?? '0' }} years</p>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Appointment Details</h6>
                        <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id ?? $appointment->id }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</p>
                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        <p><strong>Type:</strong> <span class="badge bg-info text-dark">{{ ucfirst($appointment->appointment_type) }}</span></p>
                        <p><strong>Fee:</strong> <span class="text-success">${{ number_format($appointment->fee, 2) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Status & Additional Info</h6>
                        <p><strong>Status:</strong> 
                            @php
                                $statusClass = match($appointment->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                        </p>
                        <p><strong>Reason:</strong> {{ $appointment->reason ?? 'Not specified' }}</p>
                        <p><strong>Notes:</strong> {{ $appointment->notes ?? 'No additional notes' }}</p>
                        <p><strong>Created:</strong> {{ $appointment->created_at->format('F d, Y h:i A') }}</p>
                        <p><strong>Last Updated:</strong> {{ $appointment->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
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

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Appointment - #{{ $appointment->appointment_id ?? $appointment->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_patient_id_{{ $appointment->id }}" class="form-label">Patient</label>
                                <select class="form-select" id="edit_patient_id_{{ $appointment->id }}" name="patient_id" required>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_doctor_id_{{ $appointment->id }}" class="form-label">Doctor</label>
                                <select class="form-select" id="edit_doctor_id_{{ $appointment->id }}" name="doctor_id" required>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_appointment_date_{{ $appointment->id }}" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" id="edit_appointment_date_{{ $appointment->id }}" 
                                       name="appointment_date" value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_appointment_time_{{ $appointment->id }}" class="form-label">Appointment Time</label>
                                <input type="time" class="form-control" id="edit_appointment_time_{{ $appointment->id }}" 
                                       name="appointment_time" value="{{ $appointment->appointment_time }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_appointment_type_{{ $appointment->id }}" class="form-label">Appointment Type</label>
                                <select class="form-select" id="edit_appointment_type_{{ $appointment->id }}" name="appointment_type" required>
                                    <option value="consultation" {{ $appointment->appointment_type == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="checkup" {{ $appointment->appointment_type == 'checkup' ? 'selected' : '' }}>Checkup</option>
                                    <option value="surgery" {{ $appointment->appointment_type == 'surgery' ? 'selected' : '' }}>Surgery</option>
                                    <option value="followup" {{ $appointment->appointment_type == 'followup' ? 'selected' : '' }}>Follow-up</option>
                                    <option value="emergency" {{ $appointment->appointment_type == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_fee_{{ $appointment->id }}" class="form-label">Fee ($)</label>
                                <input type="number" class="form-control" id="edit_fee_{{ $appointment->id }}" name="fee" 
                                       value="{{ $appointment->fee }}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_reason_{{ $appointment->id }}" class="form-label">Reason for Appointment</label>
                        <textarea class="form-control" id="edit_reason_{{ $appointment->id }}" name="reason" rows="3">{{ $appointment->reason }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes_{{ $appointment->id }}" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="edit_notes_{{ $appointment->id }}" name="notes" rows="2">{{ $appointment->notes }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_status_{{ $appointment->id }}" class="form-label">Status</label>
                        <select class="form-select" id="edit_status_{{ $appointment->id }}" name="status" required>
                            <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Appointment Modal -->
<div class="modal fade" id="deleteAppointmentModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5>Are you sure you want to delete this appointment?</h5>
                <p class="mb-1"><strong>#{{ $appointment->appointment_id ?? $appointment->id }}</strong></p>
                <p class="text-muted">
                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} with 
                    Dr. {{ $appointment->doctor->name }}
                </p>
                <p class="text-muted">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} at 
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </p>
                <div class="alert alert-warning mt-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        This action cannot be undone. All appointment data will be permanently removed.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
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
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        font-weight: bold;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
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
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
            margin-right: 0;
        }
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

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add confirmation for delete actions
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });

        // Date validation - prevent past dates
        const appointmentDateInputs = document.querySelectorAll('input[type="date"][name="appointment_date"]');
        appointmentDateInputs.forEach(function(input) {
            const today = new Date().toISOString().split('T')[0];
            input.setAttribute('min', today);
            
            input.addEventListener('change', function(e) {
                const selectedDate = new Date(e.target.value);
                const today = new Date();
                if (selectedDate < today) {
                    alert('Appointment date cannot be in the past');
                    e.target.value = today.toISOString().split('T')[0];
                }
            });
        });

        // Time validation
        const appointmentTimeInputs = document.querySelectorAll('input[type="time"][name="appointment_time"]');
        appointmentTimeInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const selectedTime = e.target.value;
                const [hours, minutes] = selectedTime.split(':').map(Number);
                
                // Validate business hours (8 AM to 8 PM)
                if (hours < 8 || hours >= 20) {
                    alert('Appointments are only available between 8:00 AM and 8:00 PM');
                    e.target.value = '08:00';
                }
            });
        });

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }
    });
</script>
@endsection