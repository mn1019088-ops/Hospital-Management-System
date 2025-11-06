@extends('layouts.doctor')

@section('title', 'Edit Appointment')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
        <h1 class="h2">Edit Appointment</h1>
        <a href="{{ route('doctor.appointments') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Appointments
        </a>
    </div>

    <!-- Edit Appointment Form -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Appointment #{{ $appointment->appointment_id }}
            </h5>
        </div>
        <div class="card-body">
            <!-- Patient Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">Patient Information</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Name:</strong><br>
                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Patient ID:</strong><br>
                            {{ $appointment->patient->patient_id }}
                        </div>
                        <div class="col-md-3">
                            <strong>Age:</strong><br>
                            {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . ' years' : 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Gender:</strong><br>
                            {{ ucfirst($appointment->patient->gender) }}
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="appointment_date" 
                                   id="appointment_date" 
                                   class="form-control @error('appointment_date') is-invalid @enderror"
                                   value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                                   required
                                   min="{{ date('Y-m-d') }}">
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   name="appointment_time" 
                                   id="appointment_time" 
                                   class="form-control @error('appointment_time') is-invalid @enderror"
                                   value="{{ old('appointment_time', $appointment->appointment_time) }}" 
                                   required>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="appointment_type" class="form-label">Appointment Type <span class="text-danger">*</span></label>
                            <select name="appointment_type" 
                                    id="appointment_type" 
                                    class="form-select @error('appointment_type') is-invalid @enderror" 
                                    required>
                                <option value="">Select Type</option>
                                @foreach($appointmentTypes as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ old('appointment_type', $appointment->appointment_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required>
                                <option value="">Select Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ old('status', $appointment->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Visit <span class="text-danger">*</span></label>
                    <textarea name="reason" 
                              id="reason" 
                              class="form-control @error('reason') is-invalid @enderror" 
                              rows="3" 
                              placeholder="Enter the reason for appointment..."
                              required>{{ old('reason', $appointment->reason) }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" 
                              id="notes" 
                              class="form-control @error('notes') is-invalid @enderror" 
                              rows="3" 
                              placeholder="Enter any additional notes...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Update Appointment
                        </button>
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                    
                    <div class="text-muted small">
                        Last updated: 
                        @if($appointment->updated_at)
                            {{ $appointment->updated_at->format('M d, Y h:i A') }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Appointment Details -->
    <div class="card border-light shadow-sm mt-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Current Appointment Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Original Date:</strong><br>
                    {{ $appointment->appointment_date->format('M d, Y') }}
                </div>
                <div class="col-md-3">
                    <strong>Original Time:</strong><br>
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </div>
                <div class="col-md-3">
                    <strong>Type:</strong><br>
                    <span class="badge bg-info text-dark">{{ ucfirst($appointment->appointment_type) }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Current Status:</strong><br>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('appointment_date').min = today;

    // Show confirmation before leaving if form has changes
    let formChanged = false;
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    form.addEventListener('submit', function() {
        formChanged = false;
    });
});
</script>
@endsection