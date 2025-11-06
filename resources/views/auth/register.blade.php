<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MediCare Hospital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary: #2c5aa0;
        --primary-dark: #1e3d72;
        --primary-light: #4a7bc8;
        --secondary: #34a853;
        --accent: #fbbc05;
        --danger: #ea4335;
        --warning: #f29900;
        --success: #27ae60;
        --light: #f8f9fa;
        --dark: #2d3748;
        --gray: #6c757d;
        --patient-color: #8e44ad;
        --patient-dark: #6c3483;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        line-height: 1.6;
    }

    .register-wrapper {
        display: flex;
        max-width: 1300px;
        width: 100%;
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin: 0 auto;
    }

    .register-container {
        flex: 1;
        max-width: 550px;
        padding: 0;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .register-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
        flex-shrink: 0;
    }

    .hospital-logo {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .hospital-logo i {
        font-size: 2.5rem;
        color: var(--primary);
    }

    .register-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .register-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .register-body {
        padding: 40px 30px;
        background: white;
        flex: 1;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }

    .role-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .role-btn {
        border: 2px solid #e2e8f0;
        background: white;
        padding: 16px 8px;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .role-btn.active {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .role-btn.doctor.active {
        border-color: var(--success);
        background: linear-gradient(135deg, var(--success) 0%, #27ae60 100%);
    }

    .role-btn.reception.active {
        border-color: var(--warning);
        background: linear-gradient(135deg, var(--warning) 0%, #e67e22 100%);
    }

    .role-btn.patient.active {
        border-color: var(--patient-color);
        background: linear-gradient(135deg, var(--patient-color) 0%, var(--patient-dark) 100%);
    }

    .role-btn i {
        display: block;
        font-size: 1.75rem;
        margin-bottom: 8px;
    }

    .role-btn .role-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .role-btn .role-description {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 4px;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px 14px 48px;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
        height: 54px;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 70%;
        transform: translateY(-50%);
        color: #2c5aa0;
        z-index: 5;
        pointer-events: none;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .password-toggle {
        position: absolute;
        right: 5px;
        top: 70%;
        transform: translateY(-40%);
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        z-index: 10;
        font-size: 1.1rem;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--primary);
    }

    .role-specific-fields {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        border-left: 4px solid var(--primary);
        display: none;
    }

    .role-specific-fields.active {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .role-specific-fields.patient-fields {
        border-left-color: var(--patient-color);
    }

    .role-specific-fields.doctor-fields {
        border-left-color: var(--success);
    }

    .role-specific-fields.reception-fields {
        border-left-color: var(--warning);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .password-strength {
        height: 6px;
        border-radius: 3px;
        margin-top: 8px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
        width: 0;
    }

    .strength-weak { background: var(--danger); width: 25%; }
    .strength-medium { background: var(--warning); width: 50%; }
    .strength-strong { background: var(--success); width: 75%; }
    .strength-very-strong { background: var(--primary); width: 100%; }

    .password-requirements .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
        font-size: 0.8rem;
        color: var(--gray);
    }

    .requirement i {
        font-size: 0.7rem;
        margin-right: 6px;
    }

    .requirement.met { color: var(--success); }
    .requirement.met i { color: var(--success); }

    .terms-check .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        margin-top: 0.25rem;
        border: 2px solid #ced4da;
    }

    .btn-register {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 12px;
        padding: 16px;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-register:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(44, 90, 160, 0.4);
    }

    .btn-register:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .login-link-container {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .login-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .login-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Image Container */
    .image-container {
        flex: 1.2;
        background: url('https://cdn.pixabay.com/photo/2016/11/29/04/17/doctor-1869421_1280.jpg') center center/cover no-repeat;
        position: relative;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 60px 40px;
    }

    .image-container::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(13, 71, 161, 0.82);
        z-index: 0;
    }

    .image-content {
        position: relative;
        z-index: 1;
        max-width: 400px;
    }

    .image-content i {
        font-size: 4rem;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.95);
    }

    .image-content h3 {
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1.9rem;
    }

    .image-content p {
        font-size: 1.1rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    /* Toast */
    .toast-container-custom {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1100;
        max-width: 350px;
    }

    .loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: 20px;
        display: none;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Patient specific styles */
    .patient-info-card {
        background: linear-gradient(135deg, #f8f5ff 0%, #f0ebff 100%);
        border: 1px solid #e9deff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .patient-info-card h6 {
        color: var(--patient-color);
        margin-bottom: 10px;
    }

    .patient-info-card p {
        font-size: 0.85rem;
        color: #666;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .role-selector {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .register-wrapper {
            flex-direction: column;
        }
        .image-container {
            display: none;
        }
        .register-container {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .register-header, .register-body {
            padding: 30px 20px;
        }
        .role-selector {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .toast-container-custom {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
        .form-control, .btn-register {
            height: 50px;
            font-size: 0.95rem;
        }
        .input-icon, .password-toggle {
            font-size: 1rem;
        }
    }
</style>
</head>
<body>

    <div class="toast-container-custom" id="toastContainer"></div>

    <div class="register-wrapper">
        <div class="register-container">
            <div class="loading-overlay" id="loadingOverlay">
                <div class="loading-spinner"></div>
            </div>

            <div class="register-header">
                <div class="hospital-logo">
                    <i class="fas fa-hospital-alt"></i>
                </div>
                <h1 class="register-title">MediCare Hospital</h1>
                <p class="register-subtitle">Create Your Professional Account</p>
            </div>

            <div class="register-body">
                <form id="registerForm" method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="role-selector">
                        <div class="role-btn doctor active" data-role="doctor">
                            <i class="fas fa-user-md"></i>
                            <div class="role-name">Doctor</div>
                            <div class="role-description">Medical Staff</div>
                        </div>
                        <div class="role-btn reception" data-role="reception">
                            <i class="fas fa-user-tie"></i>
                            <div class="role-name">Reception</div>
                            <div class="role-description">Front Desk</div>
                        </div>
                        <div class="role-btn patient" data-role="patient">
                            <i class="fas fa-user-injured"></i>
                            <div class="role-name">Patient</div>
                            <div class="role-description">Healthcare Services</div>
                        </div>
                    </div>
                    <input type="hidden" id="user_type" name="user_type" value="doctor">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" id="name" placeholder="Enter full name"
                                       value="{{ old('name') }}" required minlength="2" maxlength="100">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" id="email" placeholder="email@example.com"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" id="phone" placeholder="9876543210"
                                       value="{{ old('phone') }}" required pattern="[6-9][0-9]{9}" maxlength="10">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" id="password" placeholder="Create password" required minlength="6">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrength"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="form-control" name="password_confirmation"
                                       id="confirmPassword" placeholder="Confirm password" required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="mt-2 small"></div>
                        </div>
                    </div>

                    <!-- Doctor Specific Fields -->
                    <div id="doctorFields" class="role-specific-fields doctor-fields active">
                        <h6 class="mb-3 text-success"><i class="fas fa-user-md me-2"></i>Doctor Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                    <i class="fas fa-stethoscope input-icon"></i>
                                    <input type="text" class="form-control @error('specialization') is-invalid @enderror"
                                           name="specialization" id="specialization" placeholder="e.g., Cardiology"
                                           value="{{ old('specialization') }}">
                                    @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Qualification <span class="text-danger">*</span></label>
                                    <i class="fas fa-graduation-cap input-icon"></i>
                                    <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                                           name="qualification" id="qualification" placeholder="e.g., MBBS, MD"
                                           value="{{ old('qualification') }}">
                                    @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Experience (Years) <span class="text-danger">*</span></label>
                                    <i class="fas fa-briefcase input-icon"></i>
                                    <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                                           name="experience_years" id="experience_years" placeholder="Years"
                                           value="{{ old('experience_years') }}" min="0" max="50">
                                    @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reception Specific Fields -->
                    <div id="receptionFields" class="role-specific-fields reception-fields">
                        <h6 class="mb-3 text-warning"><i class="fas fa-user-tie me-2"></i>Reception Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                                    <i class="fas fa-id-badge input-icon"></i>
                                    <input type="text" class="form-control" name="employee_id" id="employee_id"
                                           placeholder="EMP12345" value="{{ old('employee_id') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Specific Fields -->
                    <div id="patientFields" class="role-specific-fields patient-fields">
                        <h6 class="mb-3 text-purple"><i class="fas fa-user-injured me-2"></i>Patient Information</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                           name="date_of_birth" id="date_of_birth" 
                                           value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}">
                                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <i class="fas fa-venus-mars input-icon"></i>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Blood Group</label>
                                    <i class="fas fa-tint input-icon"></i>
                                    <select class="form-control" name="blood_group" id="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Emergency Contact</label>
                                    <i class="fas fa-phone-alt input-icon"></i>
                                    <input type="tel" class="form-control" name="emergency_contact" id="emergency_contact"
                                           placeholder="Emergency phone number" value="{{ old('emergency_contact') }}"
                                           pattern="[6-9][0-9]{9}" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <i class="fas fa-home input-icon"></i>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      name="address" id="address" rows="3" 
                                      placeholder="Enter your complete address">{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Medical History</label>
                                    <i class="fas fa-file-medical input-icon"></i>
                                    <textarea class="form-control" name="medical_history" id="medical_history" 
                                              rows="2" placeholder="Any previous medical conditions">{{ old('medical_history') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Allergies</label>
                                    <i class="fas fa-allergies input-icon"></i>
                                    <textarea class="form-control" name="allergies" id="allergies" 
                                              rows="2" placeholder="Any known allergies">{{ old('allergies') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-check mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                   name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-primary">Terms</a> and
                                <a href="#" class="text-primary">Privacy Policy</a>
                            </label>
                            @error('terms') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-register" id="registerButton">
                        Create Account
                    </button>
                </form>

                <div class="login-link-container">
                    <p class="text-muted mb-0">Already have an account?
                        <a href="{{ route('login') }}" class="login-link">Sign In</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="image-container d-none d-lg-flex">
            <div class="image-content">
                <i class="fas fa-stethoscope"></i>
                <h3>Excellence in Healthcare</h3>
                <p>Join our team and contribute to world-class patient care with advanced hospital management technology.</p>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    class RegistrationForm {
        constructor() { this.init(); }

        init() {
            this.setupRoleSelection();
            this.setupPasswordToggle();
            this.setupRealTimeValidation();
            this.setupFormSubmission();
            this.setupPhoneValidation();
            this.setupDateOfBirthValidation();
            this.showLaravelMessages();
        }

        setupRoleSelection() {
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const role = btn.dataset.role;
                    document.getElementById('user_type').value = role;
                    this.toggleRoleFields(role);
                    this.showToast('info', `Selected: ${role.charAt(0).toUpperCase() + role.slice(1)}`);
                });
            });
        }

        toggleRoleFields(role) {
            ['doctorFields', 'receptionFields', 'patientFields'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.toggle('active', el.id === role + 'Fields');
            });
            
            // Set required fields based on role
            const isPatient = role === 'patient';
            const isDoctor = role === 'doctor';
            const isReception = role === 'reception';
            
            this.setRequired('#patientFields input, #patientFields select, #patientFields textarea', isPatient);
            this.setRequired('#doctorFields input, #doctorFields select', isDoctor);
            this.setRequired('#receptionFields input', isReception);
        }

        setRequired(selector, required) {
            document.querySelectorAll(selector).forEach(field => {
                field.required = required;
                // Update visual required indicator
                const label = field.closest('.form-group')?.querySelector('.form-label');
                if (label) {
                    const star = label.querySelector('.text-danger');
                    if (required && !star) {
                        label.innerHTML += ' <span class="text-danger">*</span>';
                    } else if (!required && star) {
                        star.remove();
                    }
                }
            });
        }

        setupPasswordToggle() {
            ['password', 'confirmPassword'].forEach(id => {
                const input = document.getElementById(id);
                const toggle = document.getElementById('toggle' + id.charAt(0).toUpperCase() + id.slice(1));
                if (toggle) {
                    toggle.addEventListener('click', () => {
                        const type = input.type === 'password' ? 'text' : 'password';
                        input.type = type;
                        toggle.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                    });
                }
            });
        }

        setupRealTimeValidation() {
            document.getElementById('password').addEventListener('input', e => {
                this.checkPasswordStrength(e.target.value);
                this.checkPasswordMatch();
            });
            document.getElementById('confirmPassword').addEventListener('input', () => this.checkPasswordMatch());

            document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
                field.addEventListener('blur', () => this.validateField(field));
                field.addEventListener('input', () => field.classList.contains('is-invalid') && this.validateField(field));
            });
        }

        setupDateOfBirthValidation() {
            const dobField = document.getElementById('date_of_birth');
            if (dobField) {
                dobField.addEventListener('change', () => this.validateDateOfBirth(dobField));
            }
        }

        validateDateOfBirth(field) {
            const dob = new Date(field.value);
            const today = new Date();
            const minAge = new Date();
            minAge.setFullYear(today.getFullYear() - 120); // 120 years max age
            const maxAge = new Date();
            maxAge.setDate(today.getDate() - 1); // Yesterday minimum

            let valid = true;
            let msg = '';

            if (dob > maxAge) {
                valid = false;
                msg = 'Date of birth cannot be in the future.';
            } else if (dob < minAge) {
                valid = false;
                msg = 'Please enter a valid date of birth.';
            }

            this.updateValidation(field, valid, msg);
            return valid;
        }

        checkPasswordStrength(pwd) {
            const checks = {
                length: pwd.length >= 6,
                uppercase: /[A-Z]/.test(pwd),
                lowercase: /[a-z]/.test(pwd),
                number: /\d/.test(pwd),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(pwd)
            };
            let met = Object.values(checks).filter(Boolean).length;
            const bar = document.getElementById('passwordStrength');
            bar.className = `password-strength-bar strength-${met === 5 ? 'very-strong' : met >= 4 ? 'strong' : met >= 3 ? 'medium' : 'weak'}`;
        }

        checkPasswordMatch() {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('confirmPassword').value;
            const match = document.getElementById('passwordMatch');
            if (!p2) return match.innerHTML = '';
            match.innerHTML = p1 === p2
                ? '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match</span>'
                : '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</span>';
        }

        validateField(field) {
            const val = field.value.trim();
            let valid = true, msg = '';

            if (field.name === 'name' && (val.length < 2 || val.length > 100)) valid = false, msg = 'Name must be 2–100 characters.';
            if (field.name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) valid = false, msg = 'Invalid email.';
            if (field.name === 'phone' && !/^[6-9]\d{9}$/.test(val)) valid = false, msg = 'Invalid phone (10 digits, start 6-9).';
            if (field.name === 'date_of_birth' && field.required && !val) valid = false, msg = 'Date of birth is required.';
            if (field.name === 'gender' && field.required && !val) valid = false, msg = 'Gender is required.';

            this.updateValidation(field, valid, msg);
            return valid;
        }

        updateValidation(field, valid, msg) {
            field.classList.toggle('is-valid', valid);
            field.classList.toggle('is-invalid', !valid);
            const feedback = field.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = msg;
        }

        setupPhoneValidation() {
            const phone = document.getElementById('phone');
            const emergencyContact = document.getElementById('emergency_contact');
            
            [phone, emergencyContact].forEach(field => {
                if (field) {
                    field.addEventListener('input', e => {
                        let v = e.target.value.replace(/\D/g, '').slice(0, 10);
                        e.target.value = v;
                    });
                }
            });
        }

        setupFormSubmission() {
            document.getElementById('registerForm').addEventListener('submit', e => {
                if (!this.validateForm()) {
                    e.preventDefault();
                    this.showToast('error', 'Please fix the errors before submitting.');
                } else {
                    this.showLoading(true);
                }
            });
        }

        validateForm() {
            let valid = true;
            const role = document.getElementById('user_type').value;
            
            // Validate common fields
            document.querySelectorAll('input[required], select[required], textarea[required]').forEach(f => {
                if (!this.validateField(f)) valid = false;
            });

            // Validate role-specific required fields
            if (role === 'patient') {
                const dobValid = this.validateDateOfBirth(document.getElementById('date_of_birth'));
                if (!dobValid) valid = false;
            }

            if (!document.getElementById('terms').checked) {
                valid = false;
                this.showToast('error', 'You must agree to the terms and conditions.');
            }

            if (document.getElementById('password').value !== document.getElementById('confirmPassword').value) {
                valid = false;
                this.showToast('error', 'Passwords do not match.');
            }

            return valid;
        }

        showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            const btn = document.getElementById('registerButton');
            overlay.style.display = show ? 'flex' : 'none';
            btn.disabled = show;
            btn.innerHTML = show ? '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...' : 'Create Account';
        }

        showToast(type, msg) {
            const container = document.getElementById('toastContainer');
            const id = 'toast-' + Date.now();
            const bg = { success: 'bg-success', error: 'bg-danger', warning: 'bg-warning text-dark', info: 'bg-info' }[type];
            const icon = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' }[type];
            const html = `
                <div id="${id}" class="toast align-items-center text-white ${bg} border-0 mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <i class="fas ${icon} me-2"></i>${msg}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            const toast = new bootstrap.Toast(document.getElementById(id), { delay: 5000 });
            toast.show();
            document.getElementById(id).addEventListener('hidden.bs.toast', () => document.getElementById(id).remove());
        }

        showLaravelMessages() {
            @if (session('success')) this.showToast('success', "{{ session('success') }}"); @endif
            @if (session('error')) this.showToast('error', "{{ session('error') }}"); @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    this.showToast('error', "{{ $error }}");
                @endforeach
            @endif
        }
    }

    document.addEventListener('DOMContentLoaded', () => new RegistrationForm());
</script>
</body>
</html>