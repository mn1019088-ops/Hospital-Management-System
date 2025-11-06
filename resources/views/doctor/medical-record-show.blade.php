@extends('layouts.doctor')

@section('title', 'Medical Record Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-medical me-2"></i>Medical Record Details
    </h1>
    <div>
        <a href="{{ route('doctor.medical-records.edit', $medicalRecord->id) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit Record
        </a>
        <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Records
        </a>
        <button class="btn btn-success" onclick="printMedicalRecord()">
            <i class="fas fa-print me-2"></i>Print
        </button>
    </div>
</div>

<!-- Action Buttons for Mobile -->
<div class="d-grid gap-2 d-md-none mb-4">
    <a href="{{ route('doctor.medical-records.edit', $medicalRecord->id) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Edit Record
    </a>
    <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Records
    </a>
    <button class="btn btn-success" onclick="printMedicalRecord()">
        <i class="fas fa-print me-2"></i>Print Record
    </button>
</div>

<div class="card border-primary shadow-lg">
    <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-medical-alt me-2"></i>Record ID: {{ $medicalRecord->record_id }}
        </h5>
        <span class="fw-bold">
            <i class="fas fa-hospital me-2"></i>{{ $medicalRecord->hospital_name ?? 'MediCare Hospital' }}
        </span>
    </div>

    <div class="card-body">
        <!-- Patient and Doctor Information -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <h5 class="text-primary border-bottom pb-2">
                    <i class="fas fa-user me-2"></i>Patient Information
                </h5>
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($medicalRecord->patient->profile_image)
                                <img src="{{ asset('storage/' . $medicalRecord->patient->profile_image) }}" 
                                     alt="{{ $medicalRecord->patient->first_name }}" 
                                     class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0 text-dark">{{ $medicalRecord->patient->first_name }} {{ $medicalRecord->patient->last_name }}</h5>
                                <p class="text-muted mb-0">ID: {{ $medicalRecord->patient->patient_id }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Age:</strong> {{ $medicalRecord->patient->date_of_birth ? \Carbon\Carbon::parse($medicalRecord->patient->date_of_birth)->age . ' years' : 'N/A' }}
                            </div>
                            <div class="col-6">
                                <strong>Gender:</strong> {{ ucfirst($medicalRecord->patient->gender) }}
                            </div>
                            <div class="col-6 mt-2">
                                <strong>Blood Group:</strong>
                                @if($medicalRecord->patient->blood_group)
                                    <span class="badge bg-danger">{{ $medicalRecord->patient->blood_group }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </div>
                            <div class="col-6 mt-2">
                                <strong>Phone:</strong> {{ $medicalRecord->patient->phone ?? 'N/A' }}
                            </div>
                            @if($medicalRecord->patient->email)
                            <div class="col-12 mt-2">
                                <strong>Email:</strong> {{ $medicalRecord->patient->email }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <h5 class="text-primary border-bottom pb-2">
                    <i class="fas fa-user-md me-2"></i>Doctor Information
                </h5>
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($medicalRecord->doctor->profile_image)
                                <img src="{{ asset('storage/' . $medicalRecord->doctor->profile_image) }}" 
                                     alt="Dr. {{ $medicalRecord->doctor->name }}" 
                                     class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user-md fa-lg"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0 text-dark">Dr. {{ $medicalRecord->doctor->name }}</h5>
                                <p class="text-muted mb-0">{{ $medicalRecord->doctor->specialization }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Qualification:</strong> {{ $medicalRecord->doctor->qualification }}
                            </div>
                            <div class="col-12 mt-2">
                                <strong>License No:</strong> {{ $medicalRecord->doctor->license_number ?? 'N/A' }}
                            </div>
                            @if($medicalRecord->doctor->experience_years)
                            <div class="col-12 mt-2">
                                <strong>Experience:</strong> {{ $medicalRecord->doctor->experience_years }} years
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visit Information -->
        <div class="mb-4">
            <h5 class="text-primary border-bottom pb-2">
                <i class="fas fa-calendar-alt me-2"></i>Visit Information
            </h5>
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-2">
                            <div class="border-end border-primary pe-3">
                                <strong class="d-block text-muted">Visit Date</strong>
                                <span class="h5 text-primary">{{ $medicalRecord->visit_date->format('F d, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="border-end border-primary pe-3">
                                <strong class="d-block text-muted">Record Created</strong>
                                <span class="h6 text-success">{{ $medicalRecord->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong class="d-block text-muted">Last Updated</strong>
                            <span class="h6 text-info">{{ $medicalRecord->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Details -->
        <div class="mb-4">
            <h5 class="text-primary border-bottom pb-2">
                <i class="fas fa-stethoscope me-2"></i>Medical Details
            </h5>
            <div class="card border-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Symptoms
                            </h6>
                            <div class="bg-light p-3 rounded border">
                                {{ $medicalRecord->symptoms }}
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="text-warning">
                                <i class="fas fa-diagnoses me-2"></i>Diagnosis
                            </h6>
                            <div class="bg-light p-3 rounded border">
                                {{ $medicalRecord->diagnosis }}
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="text-warning">
                                <i class="fas fa-pills me-2"></i>Treatment
                            </h6>
                            <div class="bg-light p-3 rounded border">
                                {{ $medicalRecord->treatment }}
                            </div>
                        </div>
                        @if($medicalRecord->prescription)
                        <div class="col-12 mb-3">
                            <h6 class="text-warning">
                                <i class="fas fa-prescription me-2"></i>Prescription
                            </h6>
                            <div class="bg-light p-3 rounded border">
                                {{ $medicalRecord->prescription }}
                            </div>
                        </div>
                        @endif
                        @if($medicalRecord->notes)
                        <div class="col-12">
                            <h6 class="text-warning">
                                <i class="fas fa-sticky-note me-2"></i>Additional Notes
                            </h6>
                            <div class="bg-light p-3 rounded border">
                                {{ $medicalRecord->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="mb-4">
            <h5 class="text-primary border-bottom pb-2">
                <i class="fas fa-heartbeat me-2"></i>Vital Signs
            </h5>
            <div class="card border-danger">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-primary rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">Weight</strong>
                                <div class="fs-4 text-primary fw-bold">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-success rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">Height</strong>
                                <div class="fs-4 text-success fw-bold">{{ $medicalRecord->height ? $medicalRecord->height . ' cm' : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-danger rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">Blood Pressure</strong>
                                <div class="fs-4 text-danger fw-bold">{{ $medicalRecord->blood_pressure ?: 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-warning rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">BMI</strong>
                                <div class="fs-4 text-warning fw-bold">{{ $medicalRecord->bmi ? number_format($medicalRecord->bmi, 1) : 'N/A' }}</div>
                                @if($medicalRecord->bmi_category)
                                    <div class="small text-muted">({{ $medicalRecord->bmi_category }})</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-info rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">Temperature</strong>
                                <div class="fs-4 text-info fw-bold">{{ $medicalRecord->temperature ? $medicalRecord->temperature . ' °C' : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="border border-purple rounded p-3 bg-light">
                                <strong class="d-block text-muted mb-2">Pulse Rate</strong>
                                <div class="fs-4 text-purple fw-bold">{{ $medicalRecord->pulse_rate ? $medicalRecord->pulse_rate . ' bpm' : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer bg-light">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    This medical record is confidential and intended for authorized medical personnel only.
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    Generated on: {{ now()->format('F d, Y \\a\\t h:i A') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Section -->
<div id="print-section" style="display:none;">
    <div style="text-align:center; border-bottom:3px solid #007bff; margin-bottom:20px; padding-bottom:15px;">
        <h1 style="color:#007bff; margin-bottom:5px;">MediCare Hospital</h1>
        <p style="margin:0; color:#666;">123 Healthcare Street, Medical City | +1 (555) 123-4567</p>
        <h2 style="color:#007bff; margin-top:15px;">MEDICAL RECORD DETAILS</h2>
        <p style="margin:0;"><strong>Record ID:</strong> {{ $medicalRecord->record_id }}</p>
    </div>

    <h3 style="color:#007bff; border-bottom:2px solid #007bff; padding-bottom:5px;">Patient Information</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Name:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->patient->first_name }} {{ $medicalRecord->patient->last_name }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Patient ID:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->patient->patient_id }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Age:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->patient->date_of_birth ? \Carbon\Carbon::parse($medicalRecord->patient->date_of_birth)->age . ' years' : 'N/A' }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Gender:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ ucfirst($medicalRecord->patient->gender) }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Blood Group:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->patient->blood_group ?: 'Not set' }}</td></tr>
        <tr><td style="padding:8px 0;"><strong>Phone:</strong></td><td style="padding:8px 0;">{{ $medicalRecord->patient->phone ?? 'N/A' }}</td></tr>
    </table>

    <h3 style="color:#28a745; border-bottom:2px solid #28a745; padding-bottom:5px;">Doctor Information</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Name:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">Dr. {{ $medicalRecord->doctor->name }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Specialization:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->doctor->specialization }}</td></tr>
        <tr><td style="padding:8px 0;"><strong>Qualification:</strong></td><td style="padding:8px 0;">{{ $medicalRecord->doctor->qualification }}</td></tr>
    </table>

    <h3 style="color:#17a2b8; border-bottom:2px solid #17a2b8; padding-bottom:5px;">Visit Information</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Visit Date:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->visit_date->format('F d, Y') }}</td></tr>
        <tr><td style="padding:8px 0; border-bottom:1px solid #eee;"><strong>Record Created:</strong></td><td style="padding:8px 0; border-bottom:1px solid #eee;">{{ $medicalRecord->created_at->format('F d, Y h:i A') }}</td></tr>
        <tr><td style="padding:8px 0;"><strong>Last Updated:</strong></td><td style="padding:8px 0;">{{ $medicalRecord->updated_at->format('F d, Y h:i A') }}</td></tr>
    </table>

    <h3 style="color:#ffc107; border-bottom:2px solid #ffc107; padding-bottom:5px;">Medical Details</h3>
    <div style="border:2px solid #ffc107; padding:15px; border-radius:8px; margin-bottom:20px; background-color:#fff9e6;">
        <p><strong style="color:#ffc107;">Symptoms:</strong><br>{{ $medicalRecord->symptoms }}</p>
        <p><strong style="color:#ffc107;">Diagnosis:</strong><br>{{ $medicalRecord->diagnosis }}</p>
        <p><strong style="color:#ffc107;">Treatment:</strong><br>{{ $medicalRecord->treatment }}</p>
        @if($medicalRecord->prescription)
            <p><strong style="color:#ffc107;">Prescription:</strong><br>{{ $medicalRecord->prescription }}</p>
        @endif
        @if($medicalRecord->notes)
            <p><strong style="color:#ffc107;">Additional Notes:</strong><br>{{ $medicalRecord->notes }}</p>
        @endif
    </div>

    <h3 style="color:#dc3545; border-bottom:2px solid #dc3545; padding-bottom:5px;">Vital Signs</h3>
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:15px; margin-bottom:20px;">
        <div style="border:2px solid #007bff; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#007bff;">Weight</strong>
            <div style="color:#007bff; font-size:18px; font-weight:bold;">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : 'N/A' }}</div>
        </div>
        <div style="border:2px solid #28a745; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#28a745;">Height</strong>
            <div style="color:#28a745; font-size:18px; font-weight:bold;">{{ $medicalRecord->height ? $medicalRecord->height . ' cm' : 'N/A' }}</div>
        </div>
        <div style="border:2px solid #dc3545; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#dc3545;">Blood Pressure</strong>
            <div style="color:#dc3545; font-size:18px; font-weight:bold;">{{ $medicalRecord->blood_pressure ?: 'N/A' }}</div>
        </div>
        <div style="border:2px solid #ffc107; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#ffc107;">BMI</strong>
            <div style="color:#ffc107; font-size:18px; font-weight:bold;">{{ $medicalRecord->bmi ? number_format($medicalRecord->bmi, 1) : 'N/A' }}</div>
            @if($medicalRecord->bmi_category)
                <small style="color:#666;">({{ $medicalRecord->bmi_category }})</small>
            @endif
        </div>
        <div style="border:2px solid #17a2b8; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#17a2b8;">Temperature</strong>
            <div style="color:#17a2b8; font-size:18px; font-weight:bold;">{{ $medicalRecord->temperature ? $medicalRecord->temperature . ' °C' : 'N/A' }}</div>
        </div>
        <div style="border:2px solid #6f42c1; padding:15px; border-radius:10px; text-align:center; background-color:#f8f9fa;">
            <strong style="color:#6f42c1;">Pulse Rate</strong>
            <div style="color:#6f42c1; font-size:18px; font-weight:bold;">{{ $medicalRecord->pulse_rate ? $medicalRecord->pulse_rate . ' bpm' : 'N/A' }}</div>
        </div>
    </div>

    <hr style="margin-top:30px; border-top:2px solid #007bff;">
    <p style="text-align:center; color:#666; font-size:12px;">
        Generated on: {{ now()->format('F d, Y \\a\\t h:i A') }} | This document is confidential and intended for authorized medical personnel only.
    </p>
</div>

<style>
    .text-purple { 
        color: #6f42c1 !important; 
    }
    .border-purple { 
        border-color: #6f42c1 !important; 
    }
    @media (max-width: 768px) {
        .border-end { border-right: none !important; }
    }
</style>

<script>
    function printMedicalRecord() {
        const content = document.getElementById('print-section').innerHTML;
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Medical Record - {{ $medicalRecord->record_id }}</title>
                <style>
                    body { 
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                        margin: 20px; 
                        line-height: 1.6;
                        color: #333;
                    }
                    table { width: 100%; border-collapse: collapse; }
                    table td { padding: 8px 10px; vertical-align: top; }
                    @media print { 
                        body { 
                            -webkit-print-color-adjust: exact; 
                            color-adjust: exact;
                            margin: 0;
                            padding: 15px;
                        }
                        @page { margin: 1cm; }
                    }
                    .no-print { display: none !important; }
                </style>
            </head>
            <body>
                ${content}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection