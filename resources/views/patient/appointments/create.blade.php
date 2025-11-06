@extends('layouts.patient')

@section('title', 'Book New Appointment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Book New Appointment</h1>
        <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Appointments
        </a>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Appointment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient.appointments.store') }}" method="POST" id="appointmentForm">
                        @csrf
                        
                        <!-- Hidden patient_id field -->
                        <input type="hidden" name="patient_id" value="{{ Auth::guard('patient')->user()->id }}">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label">Select Doctor *</label>
                                <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                        id="doctor_id" name="doctor_id" required>
                                    <option value="">Choose a Doctor</option>
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                        data-specialization="{{ $doctor->specialization }}">
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
                                       value="{{ old('appointment_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Appointment Time *</label>
                                <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                       name="appointment_time" id="appointment_time" 
                                       value="{{ old('appointment_time') }}" required>
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
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>
                                        Consultation
                                    </option>
                                    <option value="checkup" {{ old('appointment_type') == 'checkup' ? 'selected' : '' }}>
                                        Checkup
                                    </option>
                                    <option value="follow-up" {{ old('appointment_type') == 'follow-up' ? 'selected' : '' }}>
                                        Follow-up
                                    </option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>
                                        Emergency
                                    </option>
                                    <option value="routine" {{ old('appointment_type') == 'routine' ? 'selected' : '' }}>
                                        Routine Checkup
                                    </option>
                                    <option value="specialist" {{ old('appointment_type') == 'specialist' ? 'selected' : '' }}>
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
                                          required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Any additional information you'd like to share...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Availability Status -->
                            <div class="col-12">
                                <div class="card" id="availabilityCard" style="display: none;">
                                    <div class="card-body py-3">
                                        <h6 class="card-title mb-2"><i class="fas fa-clock me-1"></i>Availability Check</h6>
                                        <div id="availabilityStatus" class="d-flex align-items-center">
                                            <div id="availabilitySpinner" class="spinner-border spinner-border-sm me-2" style="display: none;"></div>
                                            <span id="availabilityText">Select doctor, date, and time to check availability</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                            </button>
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Patient Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Your Information</h6>
                </div>
                <div class="card-body">
                    @php
                        $patient = Auth::guard('patient')->user();
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <!-- <div class="avatar-placeholder bg-primary rounded-circle me-3">
                            <i class="fas fa-user text-white"></i>
                        </div> -->
                        <div>
                            <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                            <small class="text-muted">Patient ID: {{ $patient->patient_id }}</small>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-muted">Email</small>
                            <div>{{ $patient->email ?? 'Not provided' }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Phone</small>
                            <div>{{ $patient->phone ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Type Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Appointment Types</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-primary fw-bold">Consultation</small>
                            <div class="small text-muted">General medical consultation and advice</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-primary fw-bold">Checkup</small>
                            <div class="small text-muted">Routine health examination</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-primary fw-bold">Follow-up</small>
                            <div class="small text-muted">Follow-up visit for previous treatment</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-primary fw-bold">Emergency</small>
                            <div class="small text-muted">Urgent medical attention required</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-placeholder {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item {
        border: none;
        padding: 0.5rem 0;
    }
    .availability-available {
        color: #198754;
        font-weight: bold;
    }
    .availability-unavailable {
        color: #dc3545;
        font-weight: bold;
    }
    .availability-checking {
        color: #6c757d;
    }
    .availability-error {
        color: #dc3545;
    }
    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 4px solid;
        min-width: 300px;
        max-width: 400px;
    }
    .toast-success {
        border-left-color: #198754;
    }
    .toast-error {
        border-left-color: #dc3545;
    }
    .toast-warning {
        border-left-color: #ffc107;
    }
    .toast-info {
        border-left-color: #0dcaf0;
    }
    #availabilityCard {
        border-left: 4px solid #0dcaf0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('appointment_date').min = minDate;

        // Elements
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');
        const appointmentTypeSelect = document.getElementById('appointment_type');
        const availabilityCard = document.getElementById('availabilityCard');
        const availabilityText = document.getElementById('availabilityText');
        const availabilitySpinner = document.getElementById('availabilitySpinner');
        const form = document.getElementById('appointmentForm');
        let availabilityTimeout;
        let isDoctorAvailable = false;

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type} mb-3`;
            toast.innerHTML = `
                <div class="toast-body">
                    <div class="d-flex align-items-center">
                        <i class="fas ${getToastIcon(type)} me-2"></i>
                        <div class="flex-grow-1">${message}</div>
                        <button type="button" class="btn-close" onclick="document.getElementById('${toastId}').remove()"></button>
                    </div>
                </div>
            `;
            toast.id = toastId;
            
            toastContainer.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (document.getElementById(toastId)) {
                    document.getElementById(toastId).remove();
                }
            }, 5000);
        }

        function getToastIcon(type) {
            switch(type) {
                case 'success': return 'fa-check-circle text-success';
                case 'error': return 'fa-exclamation-circle text-danger';
                case 'warning': return 'fa-exclamation-triangle text-warning';
                case 'info': return 'fa-info-circle text-info';
                default: return 'fa-info-circle text-info';
            }
        }

        // Enhanced appointment type validation
        function validateAppointmentType() {
            const appointmentType = appointmentTypeSelect.value;
            const appointmentDate = dateInput.value;
            const appointmentTime = timeInput.value;
            
            if (appointmentType === 'emergency') {
                // For emergency appointments, allow same-day booking
                const today = new Date().toISOString().split('T')[0];
                if (appointmentDate === today) {
                    const now = new Date();
                    const selectedTime = new Date(`${appointmentDate}T${appointmentTime}`);
                    
                    // Allow emergency appointments within the next 2 hours
                    const twoHoursFromNow = new Date(now.getTime() + 2 * 60 * 60 * 1000);
                    
                    if (selectedTime < twoHoursFromNow) {
                        showToast('For emergency appointments, please select a time at least 2 hours from now.', 'warning');
                        timeInput.value = '';
                        return false;
                    }
                }
            }
            
            return true;
        }

        // Check doctor availability
        function checkDoctorAvailability() {
            const doctorId = doctorSelect.value;
            const appointmentDate = dateInput.value;
            const appointmentTime = timeInput.value;
            const appointmentType = appointmentTypeSelect.value;

            // Clear previous timeout
            if (availabilityTimeout) {
                clearTimeout(availabilityTimeout);
            }

            // Show availability card only when all fields are filled
            if (doctorId && appointmentDate && appointmentTime && appointmentType) {
                availabilityCard.style.display = 'block';
                availabilitySpinner.style.display = 'inline-block';
                availabilityText.textContent = 'Checking availability...';
                availabilityText.className = 'availability-checking';
                isDoctorAvailable = false;

                // Simulate availability check
                availabilityTimeout = setTimeout(() => {
                    simulateAvailabilityCheck(doctorId, appointmentDate, appointmentTime, appointmentType);
                }, 800);
            } else {
                availabilityCard.style.display = 'none';
                isDoctorAvailable = false;
            }
        }

        // Simulate availability check
        function simulateAvailabilityCheck(doctorId, appointmentDate, appointmentTime, appointmentType) {
            try {
                setTimeout(() => {
                    availabilitySpinner.style.display = 'none';
                    
                    // For demo: 80% chance of being available
                    const available = Math.random() > 0.2;
                    
                    if (available) {
                        availabilityText.innerHTML = `<i class="fas fa-check-circle me-1"></i> Doctor is available for ${appointmentType} appointment`;
                        availabilityText.className = 'availability-available';
                        availabilityCard.style.borderLeftColor = '#198754';
                        isDoctorAvailable = true;
                    } else {
                        availabilityText.innerHTML = `<i class="fas fa-times-circle me-1"></i> Doctor is not available for ${appointmentType} appointment`;
                        availabilityText.className = 'availability-unavailable';
                        availabilityCard.style.borderLeftColor = '#dc3545';
                        isDoctorAvailable = false;
                        
                        showToast(`Doctor is not available for ${appointmentType} appointment at the selected time. Please choose a different time.`, 'warning');
                    }
                }, 500);
                
            } catch (error) {
                availabilitySpinner.style.display = 'none';
                availabilityText.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> Error checking availability`;
                availabilityText.className = 'availability-error';
                availabilityCard.style.borderLeftColor = '#dc3545';
                isDoctorAvailable = false;
                console.error('Availability check error:', error);
            }
        }

        // Event listeners for availability check
        doctorSelect.addEventListener('change', checkDoctorAvailability);
        dateInput.addEventListener('change', checkDoctorAvailability);
        timeInput.addEventListener('input', checkDoctorAvailability);
        appointmentTypeSelect.addEventListener('change', checkDoctorAvailability);

        // Appointment type specific validations
        appointmentTypeSelect.addEventListener('change', function() {
            const appointmentType = this.value;
            
            if (appointmentType === 'emergency') {
                // Allow same-day booking for emergencies
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
                
                // Show emergency warning
                showToast('Emergency appointments are prioritized. Additional fees may apply.', 'warning');
            } else {
                // Regular appointments require at least 24 hours notice
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                dateInput.min = tomorrow.toISOString().split('T')[0];
            }
            
            checkDoctorAvailability();
        });

        // Disable weekends for non-emergency appointments
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const dayOfWeek = selectedDate.getDay();
            const appointmentType = appointmentTypeSelect.value;
            
            // Allow weekends only for emergency appointments
            if ((dayOfWeek === 0 || dayOfWeek === 6) && appointmentType !== 'emergency') {
                showToast('Please select a weekday (Monday to Friday) for non-emergency appointments', 'warning');
                this.value = '';
                availabilityCard.style.display = 'none';
                return;
            }
            
            validateAppointmentType();
            checkDoctorAvailability();
        });

        // Set business hours (9 AM to 5 PM) with exceptions for emergencies
        timeInput.addEventListener('change', function() {
            const time = this.value;
            const appointmentType = appointmentTypeSelect.value;
            
            if (time) {
                const [hours, minutes] = time.split(':').map(Number);
                
                // Extended hours for emergency appointments
                if (appointmentType === 'emergency') {
                    if (hours < 8 || hours > 20) {
                        showToast('For emergency appointments, please select a time between 8:00 AM and 8:00 PM', 'warning');
                        this.value = '';
                        availabilityCard.style.display = 'none';
                        return;
                    }
                } else {
                    // Regular business hours for other appointments
                    if (hours < 9 || hours > 17 || (hours === 17 && minutes > 0)) {
                        showToast('Please select a time between 9:00 AM and 5:00 PM', 'warning');
                        this.value = '';
                        availabilityCard.style.display = 'none';
                        return;
                    }
                }
            }
            
            validateAppointmentType();
            checkDoctorAvailability();
        });

        // Enhanced form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const appointmentDate = dateInput.value;
            const appointmentTime = timeInput.value;
            const doctorId = doctorSelect.value;
            const appointmentType = appointmentTypeSelect.value;
            const reason = document.getElementById('reason').value;
            
            let hasError = false;

            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

            // Validation checks
            if (!doctorId) {
                showError('doctor_id', 'Please select a doctor');
                hasError = true;
            }

            if (!appointmentDate) {
                showError('appointment_date', 'Please select a date');
                hasError = true;
            }

            if (!appointmentTime) {
                showError('appointment_time', 'Please select a time');
                hasError = true;
            }

            if (!appointmentType) {
                showError('appointment_type', 'Please select appointment type');
                hasError = true;
            }

            if (!reason.trim()) {
                showError('reason', 'Please provide a reason');
                hasError = true;
            }

            // Validate appointment type specific rules
            if (!validateAppointmentType()) {
                hasError = true;
            }

            if (hasError) {
                showToast('Please fill in all required fields correctly', 'error');
                return false;
            }

            const selectedDateTime = new Date(appointmentDate + 'T' + appointmentTime);
            const now = new Date();
            
            // Different time validation for emergency vs regular appointments
            if (appointmentType === 'emergency') {
                // Emergency appointments must be at least 2 hours from now
                const twoHoursFromNow = new Date(now.getTime() + 2 * 60 * 60 * 1000);
                if (selectedDateTime < twoHoursFromNow) {
                    showToast('Emergency appointments must be scheduled at least 2 hours in advance', 'error');
                    return false;
                }
            } else {
                // Regular appointments must be at least 24 hours in advance
                const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                if (selectedDateTime < tomorrow) {
                    showToast('Regular appointments must be scheduled at least 24 hours in advance', 'error');
                    return false;
                }
            }

            // Check if availability check was completed and doctor is unavailable
            if (availabilityCard.style.display === 'block' && !isDoctorAvailable) {
                showToast('Doctor is not available at the selected time. Please choose a different time or check availability again.', 'error');
                return false;
            }

            // If availability check hasn't been done, show warning but allow submission
            if (availabilityCard.style.display === 'none') {
                const proceed = confirm('Availability has not been checked. Are you sure you want to book this appointment?');
                if (!proceed) {
                    return false;
                }
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Booking...';
            submitBtn.disabled = true;

            // Submit the form
            setTimeout(() => {
                this.submit();
            }, 1000);
        });

        function showError(fieldName, message) {
            const field = document.getElementById(fieldName);
            if (field) {
                field.classList.add('is-invalid');
                
                // Remove existing error feedback
                const existingError = field.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
        }

        // Show success message if redirected with success
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        // Show error message if redirected with error
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Show validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif

        // Initial check if form has values from old input
        @if(old('doctor_id') || old('appointment_date') || old('appointment_time') || old('appointment_type'))
            setTimeout(() => {
                checkDoctorAvailability();
            }, 500);
        @endif
    });
</script>
@endsection