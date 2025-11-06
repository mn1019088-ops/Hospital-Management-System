<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MediCare Hospital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary: #1a73e8;
        --primary-dark: #0d47a1;
        --secondary: #34a853;
        --danger: #ea4335;
        --warning: #f29900;
        --success: #27ae60;
        --patient: #8e44ad;
        --patient-dark: #6c3483;
        --light: #f8f9fa;
        --gray: #6c757d;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        line-height: 1.6;
    }

    .login-wrapper {
        display: flex;
        max-width: 1300px;
        width: 100%;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .login-container {
        flex: 1;
        max-width: 500px;
    }

    .login-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 40px 30px;
        text-align: center;
    }

    .hospital-logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .hospital-logo i {
        font-size: 2.5rem;
        color: var(--primary);
    }

    .login-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .login-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .login-body {
        padding: 40px 30px;
    }

    .role-selector {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 25px;
    }

    .role-btn {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .role-btn i {
        display: block;
        font-size: 1.4rem;
        margin-bottom: 6px;
    }

    .role-btn.active {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .role-btn.admin.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }

    .role-btn.doctor.active {
        border-color: var(--success);
        background: linear-gradient(135deg, var(--success), #27ae60);
    }

    .role-btn.reception.active {
        border-color: var(--warning);
        background: linear-gradient(135deg, var(--warning), #e67e22);
    }

    .role-btn.patient.active {
        border-color: var(--patient);
        background: linear-gradient(135deg, var(--patient), var(--patient-dark));
    }

    .role-btn small {
        font-weight: 600;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 15px 14px 50px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        font-size: 1rem;
        height: 54px;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
        background-color: #fff;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 16px;
        color: var(--primary);
        font-size: 1.2rem;
        z-index: 5;
        width: 20px;
        text-align: center;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .invalid-feedback {
        display: block;
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: -8px;
        margin-bottom: 10px;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: var(--gray);
        cursor: pointer;
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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

    .btn-login:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26, 115, 232, 0.4);
    }

    .btn-login:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-loading {
        pointer-events: none;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .text-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .text-link:hover {
        text-decoration: underline;
    }

    .register-link {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        font-size: 0.95rem;
    }

    /* Image Side */
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
        background: rgba(13, 71, 161, 0.8);
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
        color: rgba(255, 255, 255, 0.9);
    }

    .image-content h3 {
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1.8rem;
    }

    .image-content p {
        font-size: 1.05rem;
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

    .toast-custom {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .toast-custom .toast-body {
        padding: 14px 16px;
        font-weight: 500;
    }

    /* Patient Welcome Message */
    .patient-welcome {
        background: linear-gradient(135deg, #f8f5ff, #f0ebff);
        border: 1px solid #e9deff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }

    .patient-welcome.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    .patient-welcome h6 {
        color: var(--patient);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .patient-welcome p {
        font-size: 0.85rem;
        color: #666;
        margin: 0;
        line-height: 1.4;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .role-selector {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .login-wrapper {
            flex-direction: column;
        }
        .image-container {
            display: none;
        }
        .login-container {
            max-width: 100%;
        }
        .toast-container-custom {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }

    @media (max-width: 576px) {
        .login-header, .login-body {
            padding: 30px 20px;
        }
        .role-selector {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .role-btn {
            padding: 10px;
            font-size: 0.8rem;
        }
        .role-btn i {
            font-size: 1.3rem;
        }
        .form-control, .btn-login {
            height: 50px;
            font-size: 0.95rem;
        }
    }
</style>
</head>
<body>

    <div class="toast-container-custom" id="toastContainer"></div>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <div class="hospital-logo">
                    <i class="fas fa-hospital-alt"></i>
                </div>
                <h1 class="login-title">MediCare Hospital</h1>
                <p class="login-subtitle">Login to Your Account</p>
            </div>

            <div class="login-body">

                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf
                    <input type="hidden" name="user_type" id="user_type" value="admin">

                    <div class="role-selector">
                        <div class="role-btn admin active" data-role="admin">
                            <i class="fas fa-user-shield"></i>
                            <small>Admin</small>
                        </div>
                        <div class="role-btn doctor" data-role="doctor">
                            <i class="fas fa-user-md"></i>
                            <small>Doctor</small>
                        </div>
                        <div class="role-btn reception" data-role="reception">
                            <i class="fas fa-user-tie"></i>
                            <small>Reception</small>
                        </div>
                        <div class="role-btn patient" data-role="patient">
                            <i class="fas fa-user-injured"></i>
                            <small>Patient</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter your email"
                               value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="btn btn-sm position-absolute" 
                                style="right: 10px; top: 12px; background: none; border: none; color: #6c757d;"
                                onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-link">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login" id="loginButton">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to System
                    </button>
                </form>

                <div class="register-link">
                    <p class="text-muted mb-0">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-link">Create Account</a>
                    </p>
                </div>

                <!-- Quick Demo Credentials -->
                <div class="demo-credentials mt-4 p-3 bg-light rounded" style="display: none;" id="demoCredentials">
                    <h6 class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Demo Credentials</h6>
                    <div class="row small">
                        <div class="col-6">
                            <strong>Email:</strong> demo@medicare.com
                        </div>
                        <div class="col-6">
                            <strong>Password:</strong> password123
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="image-container d-none d-md-flex">
            <div class="image-content">
                <i class="fas fa-stethoscope"></i>
                <h3>Advanced Healthcare Solutions</h3>
                <p>Empowering hospitals with technology-driven management and care excellence.</p>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    class LoginForm {
        constructor() {
            this.init();
        }

        init() {
            this.setupRoleSwitch();
            this.setupFormSubmit();
            this.showLaravelMessages();
            this.checkUrlRole();
            this.setupDemoCredentials();
        }

        setupRoleSwitch() {
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const role = btn.dataset.role;
                    document.getElementById('user_type').value = role;
                    
                    // Show/hide patient welcome message
                    this.togglePatientWelcome(role);
                    
                    // Update login button text
                    this.updateLoginButton(role);
                    
                    this.showToast('info', `Switched to ${this.getRoleDisplayName(role)} login`);
                });
            });
        }

        togglePatientWelcome(role) {
            const welcomeMsg = document.getElementById('patientWelcome');
            if (role === 'patient') {
                welcomeMsg.classList.add('active');
            } else {
                welcomeMsg.classList.remove('active');
            }
        }

        updateLoginButton(role) {
            const btn = document.getElementById('loginButton');
            const roleName = this.getRoleDisplayName(role);
            btn.innerHTML = `<i class="fas fa-sign-in-alt me-2"></i>Login as ${roleName}`;
        }

        getRoleDisplayName(role) {
            const roleMap = {
                'admin': 'Admin',
                'doctor': 'Doctor',
                'reception': 'Reception',
                'patient': 'Patient'
            };
            return roleMap[role] || 'User';
        }

        setupFormSubmit() {
            document.getElementById('loginForm').addEventListener('submit', (e) => {
                if (this.validateForm()) {
                    const btn = document.getElementById('loginButton');
                    const role = document.getElementById('user_type').value;
                    const roleName = this.getRoleDisplayName(role);
                    
                    btn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Logging in as ${roleName}...`;
                    btn.classList.add('btn-loading');
                    btn.disabled = true;
                    
                    // Show loading state for 2 seconds before actual submission (for demo)
                    setTimeout(() => {
                        // Form will submit normally after this
                    }, 2000);
                } else {
                    e.preventDefault();
                }
            });
        }

        validateForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let valid = true;

            // Email validation
            if (!email || !this.isValidEmail(email)) {
                this.showFieldError('email', 'Please enter a valid email address');
                valid = false;
            } else {
                this.clearFieldError('email');
            }

            // Password validation
            if (!password || password.length < 6) {
                this.showFieldError('password', 'Password must be at least 6 characters');
                valid = false;
            } else {
                this.clearFieldError('password');
            }

            return valid;
        }

        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        showFieldError(fieldName, message) {
            const field = document.getElementById(fieldName);
            field.classList.add('is-invalid');
            
            let feedback = field.parentElement.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentElement.appendChild(feedback);
            }
            feedback.textContent = message;
        }

        clearFieldError(fieldName) {
            const field = document.getElementById(fieldName);
            field.classList.remove('is-invalid');
        }

        setupDemoCredentials() {
            // Show demo credentials for demonstration purposes
            const demoCredentials = document.getElementById('demoCredentials');
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('demo') === 'true') {
                demoCredentials.style.display = 'block';
                
                // Auto-fill demo credentials
                document.getElementById('email').value = 'demo@medicare.com';
                document.getElementById('password').value = 'password123';
            }
        }

        showToast(type, message) {
            const container = document.getElementById('toastContainer');
            const id = 'toast-' + Date.now();
            const bgMap = {
                success: 'bg-success',
                error: 'bg-danger',
                warning: 'bg-warning text-dark',
                info: 'bg-info'
            };
            const iconMap = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const html = `
                <div id="${id}" class="toast toast-custom align-items-center text-white ${bgMap[type]} border-0 mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <i class="fas ${iconMap[type]} me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);

            const toastEl = document.getElementById(id);
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        showLaravelMessages() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    this.showToast('error', "{{ $error }}");
                @endforeach
            @endif

            @if (session('status'))
                this.showToast('success', "{{ session('status') }}");
            @endif

            @if (session('error'))
                this.showToast('error', "{{ session('error') }}");
            @endif

            @if (session('success'))
                this.showToast('success', "{{ session('success') }}");
            @endif
        }

        checkUrlRole() {
            const params = new URLSearchParams(window.location.search);
            const role = params.get('role');
            if (role) {
                const btn = document.querySelector(`.role-btn[data-role="${role}"]`);
                if (btn) {
                    btn.click();
                    this.showToast('info', `Welcome! You are logging in as ${this.getRoleDisplayName(role)}`);
                }
            }
        }
    }

    // Password visibility toggle function
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordField.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    // Demo login function (for demonstration purposes)
    function quickDemoLogin(role) {
        const roleBtn = document.querySelector(`.role-btn[data-role="${role}"]`);
        if (roleBtn) {
            roleBtn.click();
        }
        document.getElementById('email').value = `${role}@medicare.com`;
        document.getElementById('password').value = 'password123';
        document.getElementById('demoCredentials').style.display = 'block';
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', () => {
        new LoginForm();
        
        // Add keyboard shortcuts for demo (Ctrl + Shift + D)
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                document.getElementById('demoCredentials').style.display = 'block';
                document.getElementById('email').value = 'demo@medicare.com';
                document.getElementById('password').value = 'password123';
                new LoginForm().showToast('info', 'Demo credentials loaded!');
            }
        });
    });
</script>
</body>
</html>