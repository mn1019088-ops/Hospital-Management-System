@extends('layouts.reception')

@section('title', 'Add Patient')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient Registration</h1>
    <a href="{{ route('reception.patients.list') }}" class="btn btn-primary">
        <i class="fas fa-list me-2"></i>View All Patients
    </a>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>Patient Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reception.patients.store') }}" method="POST" id="patientForm" novalidate>
                    @csrf
                    
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}" required minlength="2" maxlength="50" pattern="[A-Za-z\s]+"
                                title="Only letters and spaces are allowed">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}" required minlength="1" maxlength="50" pattern="[A-Za-z\s]+"
                                title="Only letters and spaces are allowed">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" id="email" placeholder="email@example.com"
                                value="{{ old('email') }}" maxlength="100">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">+91</span>
                                <input type="tel" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" required 
                                    placeholder="9876543210" 
                                    title="Enter a valid 10-digit Indian mobile number starting with 6,7,8,9">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <!-- <small class="text-muted">Must be a valid 10-digit Indian mobile number starting with 6,7,8,9</small> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}" required max="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d', strtotime('-120 years')) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="age_display"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="text-primary mb-3 mt-4">
                        <i class="fas fa-heartbeat me-2"></i>Medical Information
                    </h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Group</label>
                            <select name="blood_group" class="form-control @error('blood_group') is-invalid @enderror">
                                <option value="">Select Blood Group</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">+91</span>
                                <input type="tel" id="emergency_contact" name="emergency_contact"
                                    class="form-control @error('emergency_contact') is-invalid @enderror"
                                    value="{{ old('emergency_contact') }}" 
                                    placeholder="9876543210"
                                    title="Enter a valid 10-digit Indian mobile number starting with 6,7,8,9">
                            </div>
                            @error('emergency_contact')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medical History / Allergies</label>
                        <textarea name="medical_history" rows="3" maxlength="500"
                            class="form-control @error('medical_history') is-invalid @enderror"
                            placeholder="Enter any known medical conditions or allergies">{{ old('medical_history') }}</textarea>
                        @error('medical_history')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted"><span id="char_count">0</span>/500 characters</small>
                    </div>

                    <h6 class="text-primary mb-3 mt-4">
                        <i class="fas fa-address-card me-2"></i>Contact Information
                    </h6>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="3" maxlength="255"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter full address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted"><span id="address_char_count">0</span>/255 characters</small>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" maxlength="50"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" maxlength="50"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">PIN Code</label>
                            <input type="text" name="pincode" maxlength="6" pattern="\d{6}"
                                class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode') }}" title="Enter a valid 6-digit PIN code">
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-warning me-2">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Register Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-info mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Registration Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Total Patients
                        <span class="badge bg-primary rounded-pill">{{ \App\Models\Patient::count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Patients Today
                        <span class="badge bg-success rounded-pill">{{ \App\Models\Patient::whereDate('created_at', today())->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Patients This Week
                        <span class="badge bg-warning rounded-pill">{{ \App\Models\Patient::where('created_at', '>=', now()->subWeek())->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-secondary shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Quick Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>All fields marked with <span class="text-danger">*</span> are required</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Phone numbers must be 10 digits starting with 6-9</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Double-check email and phone before submitting</li>
                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Medical history helps in better treatment</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .phone-input { 
        border-left: none !important; 
    }
    .input-group-text { 
        border-right: none !important; 
    }
    .is-invalid { 
        border-color: #dc3545 !important; 
    }
    .is-valid { 
        border-color: #198754 !important; 
    }
    .validation-message {
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Phone number validation with real-time feedback
        const phoneInput = document.getElementById('phone');
        const emergencyContactInput = document.getElementById('emergency_contact');

        function validatePhoneNumber(input, isRequired = false) {
            const value = input.value.trim();
            const phoneRegex = /^[6-9]\d{9}$/;
            let isValid = true;
            let message = '';

            // Remove existing validation messages
            const existingMessage = input.parentNode.nextElementSibling;
            if (existingMessage && existingMessage.classList.contains('validation-message')) {
                existingMessage.remove();
            }

            if (!value && isRequired) {
                isValid = false;
                message = 'Phone number is required';
            } else if (value && !phoneRegex.test(value)) {
                isValid = false;
                if (!/^[6-9]/.test(value)) {
                    message = 'Phone number must start with 6, 7, 8, or 9';
                } else if (value.length !== 10) {
                    message = 'Phone number must be exactly 10 digits';
                } else {
                    message = 'Please enter a valid phone number';
                }
            }

            // Update input styling
            input.classList.remove('is-valid', 'is-invalid');
            if (value) {
                input.classList.add(isValid ? 'is-valid' : 'is-invalid');
            }

            // Add validation message
            if (!isValid && message) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'validation-message text-danger';
                messageDiv.textContent = message;
                input.parentNode.parentNode.appendChild(messageDiv);
            }

            return isValid;
        }

        // Format phone number input (only digits, max 10)
        function formatPhoneInput(input, isRequired = false) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                e.target.value = value;
                validatePhoneNumber(input, isRequired);
            });
            
            input.addEventListener('blur', function() {
                validatePhoneNumber(input, isRequired);
            });

            input.addEventListener('keypress', function(e) {
                if (!/\d/.test(e.key)) {
                    e.preventDefault();
                }
            });
        }

        // Initialize phone input validation
        formatPhoneInput(phoneInput, true);
        formatPhoneInput(emergencyContactInput, false);

        // Character counters
        const medicalHistoryTextarea = document.querySelector('textarea[name="medical_history"]');
        const addressTextarea = document.querySelector('textarea[name="address"]');
        const charCount = document.getElementById('char_count');
        const addressCharCount = document.getElementById('address_char_count');

        function updateCharCounter(textarea, counter) {
            counter.textContent = textarea.value.length;
        }

        medicalHistoryTextarea.addEventListener('input', function() {
            updateCharCounter(this, charCount);
        });

        addressTextarea.addEventListener('input', function() {
            updateCharCounter(this, addressCharCount);
        });

        // Initialize character counters
        updateCharCounter(medicalHistoryTextarea, charCount);
        updateCharCounter(addressTextarea, addressCharCount);

        // Age calculation
        const dobInput = document.getElementById('date_of_birth');
        const ageDisplay = document.getElementById('age_display');

        function calculateAge() {
            if (dobInput.value) {
                const birthDate = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                ageDisplay.textContent = `Age: ${age} years`;
            } else {
                ageDisplay.textContent = '';
            }
        }

        dobInput.addEventListener('change', calculateAge);

        // Form validation before submission
        const form = document.getElementById('patientForm');
        form.addEventListener('submit', function(e) {
            const isPhoneValid = validatePhoneNumber(phoneInput, true);
            const isEmergencyContactValid = validatePhoneNumber(emergencyContactInput, false);
            
            if (!isPhoneValid) {
                e.preventDefault();
                phoneInput.focus();
                alert('Please fix the phone number validation errors before submitting.');
                return false;
            }

            if (!isEmergencyContactValid && emergencyContactInput.value.trim()) {
                e.preventDefault();
                emergencyContactInput.focus();
                alert('Please fix the emergency contact validation errors before submitting.');
                return false;
            }

            // Additional validation for required fields
            const requiredFields = form.querySelectorAll('[required]');
            let missingFields = [];
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    missingFields.push(field.previousElementSibling.textContent.replace('*', '').trim());
                }
            });

            if (missingFields.length > 0) {
                e.preventDefault();
                alert('Please fill in all required fields: ' + missingFields.join(', '));
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registering...';
            submitBtn.disabled = true;
        });

        // Reset form handler
        form.addEventListener('reset', function() {
            // Clear validation states
            phoneInput.classList.remove('is-valid', 'is-invalid');
            emergencyContactInput.classList.remove('is-valid', 'is-invalid');
            
            // Clear validation messages
            document.querySelectorAll('.validation-message').forEach(msg => msg.remove());
            
            // Reset character counters
            updateCharCounter(medicalHistoryTextarea, charCount);
            updateCharCounter(addressTextarea, addressCharCount);
            
            // Clear age display
            ageDisplay.textContent = '';
            
            // Re-enable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Register Patient';
            submitBtn.disabled = false;
        });

        // Auto-show toasts
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        const toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(toast => toast.show());

        // Real-time validation for all inputs
        const allInputs = form.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else if (this.type === 'email' && this.value && !this.validity.valid) {
                    this.classList.add('is-invalid');
                } else if (this.value) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });
    });
</script>
@endsection