@extends('layouts.patient')

@section('title', 'Edit Appointment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Appointment</h1>
        <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Appointments
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Appointment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient.appointments.update', $appointment->id) }}" method="POST" id="editAppointmentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label">Select Doctor *</label>
                                <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                        id="doctor_id" name="doctor_id" required>
                                    <option value="">Choose a Doctor</option>
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Appointment Date *</label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Appointment Time *</label>
                                <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                       name="appointment_time" id="appointment_time" 
                                       value="{{ old('appointment_time', $appointment->appointment_time) }}" required>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- FIXED: Appointment Type Field -->
                            <div class="col-md-6">
                                <label for="appointment_type" class="form-label">Appointment Type *</label>
                                <select class="form-select @error('appointment_type') is-invalid @enderror" 
                                        name="appointment_type" id="appointment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="consultation" {{ old('appointment_type', $appointment->appointment_type) == 'consultation' ? 'selected' : '' }}>
                                        Consultation
                                    </option>
                                    <option value="checkup" {{ old('appointment_type', $appointment->appointment_type) == 'checkup' ? 'selected' : '' }}>
                                        Checkup
                                    </option>
                                    <option value="follow-up" {{ old('appointment_type', $appointment->appointment_type) == 'follow-up' ? 'selected' : '' }}>
                                        Follow-up
                                    </option>
                                    <option value="emergency" {{ old('appointment_type', $appointment->appointment_type) == 'emergency' ? 'selected' : '' }}>
                                        Emergency
                                    </option>
                                    <option value="routine" {{ old('appointment_type', $appointment->appointment_type) == 'routine' ? 'selected' : '' }}>
                                        Routine Checkup
                                    </option>
                                    <option value="specialist" {{ old('appointment_type', $appointment->appointment_type) == 'specialist' ? 'selected' : '' }}>
                                        Specialist Visit
                                    </option>
                                </select>
                                @error('appointment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="reason" class="form-label">Reason for Appointment *</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" 
                                          id="reason" name="reason" rows="4" 
                                          placeholder="Please describe your symptoms or reason for the appointment..." 
                                          required>{{ old('reason', $appointment->reason) }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Any additional information you'd like to share...">{{ old('notes', $appointment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="fas fa-save me-1"></i>Update Appointment
                            </button>
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">Cancel</a>
                            
                            <!-- Delete Button -->
                            <button type="button" class="btn btn-danger float-end" 
                                    data-bs-toggle="modal" data-bs-target="#deleteAppointmentModal">
                                <i class="fas fa-trash me-1"></i>Delete Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Appointment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Current Appointment</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-muted">Current Type</small>
                            <div class="fw-bold text-capitalize">{{ str_replace('-', ' ', $appointment->appointment_type) }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Doctor</small>
                            <div>Dr. {{ $appointment->doctor->name }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Date & Time</small>
                            <div>{{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Status</small>
                            <div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this appointment?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. All appointment data will be permanently deleted.
                </div>
                <div class="card bg-light">
                    <div class="card-body">
                        <strong>Appointment Details:</strong><br>
                        Doctor: Dr. {{ $appointment->doctor->name }}<br>
                        Date: {{ $appointment->appointment_date->format('M d, Y') }}<br>
                        Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}<br>
                        Type: {{ ucfirst($appointment->appointment_type) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('patient.appointments.destroy', $appointment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        border: none;
        padding: 0.5rem 0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editAppointmentForm');
        const appointmentTypeSelect = document.getElementById('appointment_type');
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');

        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];

        // Appointment type specific validations
        appointmentTypeSelect.addEventListener('change', function() {
            const appointmentType = this.value;
            
            if (appointmentType === 'emergency') {
                // Allow same-day booking for emergencies
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
            } else {
                // Regular appointments require at least 24 hours notice
                dateInput.min = tomorrow.toISOString().split('T')[0];
            }
        });

        // Disable weekends for non-emergency appointments
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const dayOfWeek = selectedDate.getDay();
            const appointmentType = appointmentTypeSelect.value;
            
            // Allow weekends only for emergency appointments
            if ((dayOfWeek === 0 || dayOfWeek === 6) && appointmentType !== 'emergency') {
                alert('Please select a weekday (Monday to Friday) for non-emergency appointments');
                this.value = '';
                return;
            }
        });

        // Set business hours with exceptions for emergencies
        timeInput.addEventListener('change', function() {
            const time = this.value;
            const appointmentType = appointmentTypeSelect.value;
            
            if (time) {
                const [hours, minutes] = time.split(':').map(Number);
                
                // Extended hours for emergency appointments
                if (appointmentType === 'emergency') {
                    if (hours < 8 || hours > 20) {
                        alert('For emergency appointments, please select a time between 8:00 AM and 8:00 PM');
                        this.value = '';
                        return;
                    }
                } else {
                    // Regular business hours for other appointments
                    if (hours < 9 || hours > 17 || (hours === 17 && minutes > 0)) {
                        alert('Please select a time between 9:00 AM and 5:00 PM');
                        this.value = '';
                        return;
                    }
                }
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const appointmentDate = dateInput.value;
            const appointmentTime = timeInput.value;
            const doctorId = document.getElementById('doctor_id').value;
            const appointmentType = appointmentTypeSelect.value;
            const reason = document.getElementById('reason').value;
            
            if (!appointmentDate || !appointmentTime || !doctorId || !appointmentType || !reason) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            const selectedDateTime = new Date(appointmentDate + 'T' + appointmentTime);
            const now = new Date();
            
            if (selectedDateTime <= now) {
                e.preventDefault();
                alert('Please select a future date and time for your appointment.');
                return false;
            }

            // Show loading state
            const updateBtn = document.getElementById('updateBtn');
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            updateBtn.disabled = true;
        });
    });
</script>
@endsection