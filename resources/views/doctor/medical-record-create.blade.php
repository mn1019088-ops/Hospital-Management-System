@extends('layouts.doctor')

@section('title', 'Add Medical Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-medical me-2"></i>Add Medical Record
    </h1>
    <a href="{{ route('doctor.medical-records') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-2"></i>Back to Records
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('doctor.medical-records.store') }}" method="POST">
    @csrf

    <!-- Patient Selection -->
    <div class="card border-primary shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-user me-2"></i>Patient Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="patientSelect" class="form-label">Select Patient <span class="text-danger">*</span></label>
                    <select name="patient_id" id="patientSelect" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" 
                            {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}
                            data-age="{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}"
                            data-gender="{{ $patient->gender }}"
                            data-blood-group="{{ $patient->blood_group }}"
                            data-last-visit="{{ optional($patient->medicalRecords()->latest()->first())->visit_date?->format('M d, Y') }}">
                            {{ $patient->first_name }} {{ $patient->last_name }} (ID: {{ $patient->patient_id }})
                        </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="visit_date" class="form-label">Visit Date <span class="text-danger">*</span></label>
                    <input type="date" name="visit_date" id="visit_date" class="form-control @error('visit_date') is-invalid @enderror"
                        value="{{ old('visit_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                    @error('visit_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Patient Info -->
            <div class="row mb-3" id="patientInfo" style="display: none;">
                <div class="col-12">
                    <div class="card bg-light border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong><i class="fas fa-birthday-cake text-primary me-2"></i>Age:</strong> 
                                    <span id="patientAge">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-venus-mars text-info me-2"></i>Gender:</strong> 
                                    <span id="patientGender">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-tint text-danger me-2"></i>Blood Group:</strong> 
                                    <span id="patientBloodGroup">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-calendar-check text-success me-2"></i>Last Visit:</strong> 
                                    <span id="patientLastVisit">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs -->
    <div class="card border-info shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-heartbeat me-2"></i>Vital Signs
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="weight" class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" 
                        step="0.1" min="1" max="300" value="{{ old('weight') }}" required>
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="height" class="form-label">Height (cm) <span class="text-danger">*</span></label>
                    <input type="number" name="height" id="height" class="form-control @error('height') is-invalid @enderror" 
                        step="0.1" min="30" max="250" value="{{ old('height') }}" required>
                    @error('height')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="blood_pressure" class="form-label">Blood Pressure <span class="text-danger">*</span></label>
                    <input type="text" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" 
                        placeholder="e.g., 120/80" value="{{ old('blood_pressure') }}" required>
                    @error('blood_pressure')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="temperature" class="form-label">Temperature (°C) <span class="text-danger">*</span></label>
                    <input type="number" name="temperature" class="form-control @error('temperature') is-invalid @enderror" 
                        step="0.1" min="35" max="42" value="{{ old('temperature', 36.6) }}" required>
                    @error('temperature')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="pulse_rate" class="form-label">Pulse Rate (bpm) <span class="text-danger">*</span></label>
                    <input type="number" name="pulse_rate" class="form-control @error('pulse_rate') is-invalid @enderror" 
                        min="30" max="200" value="{{ old('pulse_rate', 72) }}" required>
                    @error('pulse_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="respiratory_rate" class="form-label">Respiratory Rate <span class="text-danger">*</span></label>
                    <input type="number" name="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" 
                        min="8" max="40" value="{{ old('respiratory_rate', 16) }}" required>
                    @error('respiratory_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="oxygen_saturation" class="form-label">O₂ Saturation (%)</label>
                    <input type="number" name="oxygen_saturation" class="form-control @error('oxygen_saturation') is-invalid @enderror" 
                        min="70" max="100" step="0.1" value="{{ old('oxygen_saturation', 98) }}">
                    @error('oxygen_saturation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BMI Calculation -->
            <div class="row mb-3" id="bmiCalculation" style="display: none;">
                <div class="col-12">
                    <div class="card bg-light border-info">
                        <div class="card-body">
                            <h6 class="card-title text-info mb-3">
                                <i class="fas fa-calculator me-2"></i>BMI Calculation
                            </h6>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <strong>Weight:</strong><br>
                                    <span id="displayWeight" class="h5">0</span> kg
                                </div>
                                <div class="col-md-4">
                                    <strong>Height:</strong><br>
                                    <span id="displayHeight" class="h5">0</span> cm
                                </div>
                                <div class="col-md-4">
                                    <strong>BMI:</strong><br>
                                    <span id="displayBMI" class="h5">0</span><br>
                                    <span id="bmiCategory" class="badge"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Details -->
    <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">
                <i class="fas fa-stethoscope me-2"></i>Medical Details
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="symptoms" class="form-label">Symptoms <span class="text-danger">*</span></label>
                <textarea name="symptoms" class="form-control @error('symptoms') is-invalid @enderror" 
                    rows="3" placeholder="Describe the patient's symptoms..." required>{{ old('symptoms') }}</textarea>
                @error('symptoms')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" 
                    rows="3" placeholder="Enter the diagnosis..." required>{{ old('diagnosis') }}</textarea>
                @error('diagnosis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="treatment" class="form-label">Treatment <span class="text-danger">*</span></label>
                <textarea name="treatment" class="form-control @error('treatment') is-invalid @enderror" 
                    rows="3" placeholder="Describe the treatment provided..." required>{{ old('treatment') }}</textarea>
                @error('treatment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="prescription" class="form-label">Prescription</label>
                <textarea name="prescription" class="form-control @error('prescription') is-invalid @enderror" 
                    rows="3" placeholder="List prescribed medications and dosage...">{{ old('prescription') }}</textarea>
                @error('prescription')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                    rows="2" placeholder="Any additional observations or recommendations...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="card border-light shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Save Medical Record
                        </button>
                        <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        All fields marked with <span class="text-danger">*</span> are required
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Patient selection functionality
        const patientSelect = document.getElementById('patientSelect');
        const patientInfo = document.getElementById('patientInfo');
        const patientAge = document.getElementById('patientAge');
        const patientGender = document.getElementById('patientGender');
        const patientBloodGroup = document.getElementById('patientBloodGroup');
        const patientLastVisit = document.getElementById('patientLastVisit');

        // BMI calculation elements
        const weightInput = document.getElementById('weight');
        const heightInput = document.getElementById('height');
        const bmiCalculation = document.getElementById('bmiCalculation');
        const displayWeight = document.getElementById('displayWeight');
        const displayHeight = document.getElementById('displayHeight');
        const displayBMI = document.getElementById('displayBMI');
        const bmiCategory = document.getElementById('bmiCategory');

        // Patient selection handler
        patientSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                patientAge.textContent = selectedOption.dataset.age;
                patientGender.textContent = selectedOption.dataset.gender;
                patientBloodGroup.textContent = selectedOption.dataset.bloodGroup || 'Not set';
                patientLastVisit.textContent = selectedOption.dataset.lastVisit || 'No previous visits';
                patientInfo.style.display = 'block';
            } else {
                patientInfo.style.display = 'none';
            }
        });

        // BMI calculation function
        function calculateBMI() {
            const weight = parseFloat(weightInput.value) || 0;
            const height = parseFloat(heightInput.value) || 0;
            
            if (weight > 0 && height > 0) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);
                
                displayWeight.textContent = weight.toFixed(1);
                displayHeight.textContent = height.toFixed(1);
                displayBMI.textContent = bmi.toFixed(1);

                let category = '';
                let badgeClass = '';
                
                if (bmi < 18.5) {
                    category = 'Underweight';
                    badgeClass = 'bg-warning text-dark';
                } else if (bmi < 25) {
                    category = 'Normal';
                    badgeClass = 'bg-success';
                } else if (bmi < 30) {
                    category = 'Overweight';
                    badgeClass = 'bg-warning text-dark';
                } else {
                    category = 'Obese';
                    badgeClass = 'bg-danger';
                }
                
                bmiCategory.textContent = category;
                bmiCategory.className = `badge ${badgeClass}`;
                bmiCalculation.style.display = 'block';
            } else {
                bmiCalculation.style.display = 'none';
            }
        }

        // Event listeners for BMI calculation
        weightInput.addEventListener('input', calculateBMI);
        heightInput.addEventListener('input', calculateBMI);

        // Initialize on page load
        if (patientSelect.value) {
            patientSelect.dispatchEvent(new Event('change'));
        }
        calculateBMI();

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
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