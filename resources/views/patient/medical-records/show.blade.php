@extends('layouts.patient')

@section('title', 'Medical Record Details - ' . $medicalRecord->record_id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Medical Record Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('patient.medical-records.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Back to Records
            </a>
            <button class="btn btn-outline-primary me-2" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
            <a href="{{ route('patient.appointments.create') }}" class="btn btn-success">
                <i class="fas fa-calendar-plus me-1"></i>Book Follow-up
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Record Overview -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Medical Record #{{ $medicalRecord->record_id }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Record ID:</th>
                                    <td class="fw-bold text-primary">{{ $medicalRecord->record_id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Doctor:</th>
                                    <td>Dr. {{ $medicalRecord->doctor->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Specialization:</th>
                                    <td>{{ $medicalRecord->doctor->specialization }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Record Date:</th>
                                    <td class="fw-bold">{{ $medicalRecord->record_date->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted">Visit Type:</th>
                                    <td>
                                        <span class="badge bg-info">Consultation</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Created:</th>
                                    <td>{{ $medicalRecord->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Last Updated:</th>
                                    <td>{{ $medicalRecord->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Duration:</th>
                                    <td>{{ $medicalRecord->created_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information Section -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>Medical Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Diagnosis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-diagnosis me-2"></i>Primary Diagnosis
                            </h6>
                            <div class="card border-primary">
                                <div class="card-body">
                                    <p class="mb-0 lead">{{ $medicalRecord->diagnosis }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Symptoms -->
                    @if($medicalRecord->symptoms)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-info mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>Reported Symptoms
                            </h6>
                            <div class="card border-info">
                                <div class="card-body">
                                    <p class="mb-0">{{ $medicalRecord->symptoms }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Treatment -->
                    @if($medicalRecord->treatment)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-hand-holding-medical me-2"></i>Treatment Plan
                            </h6>
                            <div class="card border-success">
                                <div class="card-body">
                                    <p class="mb-0">{{ $medicalRecord->treatment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Prescription -->
                    @if($medicalRecord->prescription)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-warning mb-3">
                                <i class="fas fa-pills me-2"></i>Prescription
                            </h6>
                            <div class="card border-warning bg-light">
                                <div class="card-body">
                                    <div class="prescription-content">
                                        {!! nl2br(e($medicalRecord->prescription)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($medicalRecord->notes)
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-secondary mb-3">
                                <i class="fas fa-clipboard-list me-2"></i>Doctor's Notes
                            </h6>
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <p class="mb-0">{{ $medicalRecord->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vitals Section -->
            @if($medicalRecord->vitals && count(array_filter((array)$medicalRecord->vitals)) > 0)
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2"></i>Vital Signs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if(isset($medicalRecord->vitals['blood_pressure']) && $medicalRecord->vitals['blood_pressure'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-tint fa-2x text-danger"></i>
                                </div>
                                <h6 class="text-muted">Blood Pressure</h6>
                                <h4 class="text-danger fw-bold mb-0">{{ $medicalRecord->vitals['blood_pressure'] }}</h4>
                                <small class="text-muted">mmHg</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['heart_rate']) && $medicalRecord->vitals['heart_rate'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-heart fa-2x text-primary"></i>
                                </div>
                                <h6 class="text-muted">Heart Rate</h6>
                                <h4 class="text-primary fw-bold mb-0">{{ $medicalRecord->vitals['heart_rate'] }}</h4>
                                <small class="text-muted">bpm</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['temperature']) && $medicalRecord->vitals['temperature'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-thermometer-half fa-2x text-warning"></i>
                                </div>
                                <h6 class="text-muted">Temperature</h6>
                                <h4 class="text-warning fw-bold mb-0">{{ $medicalRecord->vitals['temperature'] }}</h4>
                                <small class="text-muted">°C</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['weight']) && $medicalRecord->vitals['weight'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-weight fa-2x text-success"></i>
                                </div>
                                <h6 class="text-muted">Weight</h6>
                                <h4 class="text-success fw-bold mb-0">{{ $medicalRecord->vitals['weight'] }}</h4>
                                <small class="text-muted">kg</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['height']) && $medicalRecord->vitals['height'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-ruler-vertical fa-2x text-info"></i>
                                </div>
                                <h6 class="text-muted">Height</h6>
                                <h4 class="text-info fw-bold mb-0">{{ $medicalRecord->vitals['height'] }}</h4>
                                <small class="text-muted">cm</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['oxygen_saturation']) && $medicalRecord->vitals['oxygen_saturation'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-lungs fa-2x text-info"></i>
                                </div>
                                <h6 class="text-muted">Oxygen Saturation</h6>
                                <h4 class="text-info fw-bold mb-0">{{ $medicalRecord->vitals['oxygen_saturation'] }}</h4>
                                <small class="text-muted">%</small>
                            </div>
                        </div>
                        @endif

                        @if(isset($medicalRecord->vitals['respiratory_rate']) && $medicalRecord->vitals['respiratory_rate'])
                        <div class="col-xl-3 col-md-6">
                            <div class="vital-card text-center p-3 border rounded bg-light">
                                <div class="vital-icon mb-2">
                                    <i class="fas fa-wind fa-2x text-secondary"></i>
                                </div>
                                <h6 class="text-muted">Respiratory Rate</h6>
                                <h4 class="text-secondary fw-bold mb-0">{{ $medicalRecord->vitals['respiratory_rate'] }}</h4>
                                <small class="text-muted">breaths/min</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Doctor Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>Attending Doctor
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="doctor-avatar bg-gradient-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-md fa-2x text-white"></i>
                    </div>
                    <h5 class="mb-1">Dr. {{ $medicalRecord->doctor->name }}</h5>
                    <p class="text-muted mb-3">{{ $medicalRecord->doctor->specialization }}</p>
                    
                    <div class="doctor-info">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Experience</span>
                                <span class="fw-bold">{{ $medicalRecord->doctor->years_of_experience ?? 'N/A' }} years</span>
                            </div>
                            <!-- <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">License No.</span>
                                <span class="fw-bold text-primary">{{ $medicalRecord->doctor->license_number ?? 'N/A' }}</span>
                            </div> -->
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Contact</span>
                                <span class="fw-bold">{{ $medicalRecord->doctor->phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary mb-2" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Record
                        </button>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-success mb-2">
                            <i class="fas fa-calendar-plus me-2"></i>Book Follow-up
                        </a>
                        <a href="{{ route('patient.medical-records.index') }}" class="btn btn-info">
                            <i class="fas fa-list me-2"></i>All Records
                        </a>
                    </div>
                </div>
            </div>

            <!-- Record Summary -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Record Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="record-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Diagnosis Recorded</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        @if($medicalRecord->symptoms)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Symptoms Documented</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        @endif
                        @if($medicalRecord->treatment)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Treatment Provided</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        @endif
                        @if($medicalRecord->prescription)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Prescription Given</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        @endif
                        @if($medicalRecord->vitals && count(array_filter((array)$medicalRecord->vitals)) > 0)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Vitals Recorded</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Record Complete</span>
                            <span class="badge bg-success">Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .doctor-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .vital-card {
        transition: transform 0.2s ease-in-out;
        border: 2px solid transparent !important;
    }

    .vital-card:hover {
        transform: translateY(-2px);
        border-color: #e9ecef !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .vital-icon {
        transition: transform 0.3s ease;
    }

    .vital-card:hover .vital-icon {
        transform: scale(1.1);
    }

    .list-group-item {
        border: none;
        padding: 0.75rem 0;
        background: transparent;
    }

    .prescription-content {
        line-height: 1.6;
        white-space: pre-line;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    @media print {
        .btn-toolbar,
        .card-header.bg-primary,
        .card-header.bg-info,
        .card-header.bg-success,
        .card-header.bg-warning,
        .card-header.bg-danger,
        .card-header.bg-secondary {
            background: #fff !important;
            color: #000 !important;
            border-bottom: 2px solid #000;
        }
        
        .vital-card {
            border: 1px solid #000 !important;
            break-inside: avoid;
        }
        
        .btn {
            display: none !important;
        }
    }
</style>

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

        // Add print functionality with better formatting
        window.printRecord = function() {
            const originalTitle = document.title;
            document.title = 'Medical Record - {{ $medicalRecord->record_id }} - {{ $medicalRecord->record_date->format("Y-m-d") }}';
            window.print();
            document.title = originalTitle;
        };
    });

    // Enhanced print functionality
    function printRecord() {
        const printWindow = window.open('', '_blank');
        const recordDate = '{{ $medicalRecord->record_date->format("M d, Y") }}';
        const doctorName = 'Dr. {{ $medicalRecord->doctor->name }}';
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Medical Record - {{ $medicalRecord->record_id }}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                        line-height: 1.6;
                        color: #333;
                    }
                    .header { 
                        text-align: center; 
                        border-bottom: 3px solid #333; 
                        padding-bottom: 15px; 
                        margin-bottom: 25px; 
                    }
                    .hospital-name {
                        font-size: 24px;
                        font-weight: bold;
                        color: #2c5aa0;
                        margin-bottom: 5px;
                    }
                    .record-title {
                        font-size: 18px;
                        margin: 10px 0;
                    }
                    .section { 
                        margin-bottom: 20px; 
                        page-break-inside: avoid;
                    }
                    .section-title { 
                        font-weight: bold; 
                        border-bottom: 2px solid #ccc; 
                        padding-bottom: 8px; 
                        margin-bottom: 12px;
                        color: #2c5aa0;
                        font-size: 16px;
                    }
                    .info-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 15px;
                        margin-bottom: 20px;
                    }
                    .info-item {
                        margin-bottom: 8px;
                    }
                    .info-label {
                        font-weight: bold;
                        color: #666;
                    }
                    .content-box {
                        border: 1px solid #ddd;
                        padding: 15px;
                        margin: 10px 0;
                        border-radius: 5px;
                        background: #f9f9f9;
                    }
                    .vitals-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 10px;
                        margin: 15px 0;
                    }
                    .vital-item {
                        text-align: center;
                        border: 1px solid #ccc;
                        padding: 10px;
                        border-radius: 5px;
                    }
                    .footer { 
                        margin-top: 40px; 
                        text-align: center; 
                        font-size: 12px; 
                        color: #666;
                        border-top: 1px solid #ccc;
                        padding-top: 15px;
                    }
                    @media print {
                        body { margin: 15mm; }
                        .no-print { display: none; }
                        .section { page-break-inside: avoid; }
                    }
                    .prescription {
                        background: #fffacd;
                        border-left: 4px solid #ffd700;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="hospital-name">MediCare Hospital</div>
                    <div class="record-title">MEDICAL RECORD</div>
                    <div><strong>Record ID:</strong> {{ $medicalRecord->record_id }}</div>
                    <div><strong>Date:</strong> ${recordDate}</div>
                </div>

                <div class="info-grid">
                    <div>
                        <div class="section-title">Patient Information</div>
                        <div class="info-item"><span class="info-label">Name:</span> {{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div class="info-item"><span class="info-label">Patient ID:</span> {{ $patient->patient_id }}</div>
                        <div class="info-item"><span class="info-label">Date of Birth:</span> {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="section-title">Doctor Information</div>
                        <div class="info-item"><span class="info-label">Name:</span> ${doctorName}</div>
                        <div class="info-item"><span class="info-label">Specialization:</span> {{ $medicalRecord->doctor->specialization }}</div>
                        <div class="info-item"><span class="info-label">License:</span> {{ $medicalRecord->doctor->license_number ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">Diagnosis</div>
                    <div class="content-box">${document.querySelector('.prescription-content') ? document.querySelector('.prescription-content').textContent : '{{ $medicalRecord->diagnosis }}'}</div>
                </div>

                ${'{{ $medicalRecord->symptoms }}' ? `
                <div class="section">
                    <div class="section-title">Symptoms</div>
                    <div class="content-box">{{ $medicalRecord->symptoms }}</div>
                </div>
                ` : ''}

                ${'{{ $medicalRecord->treatment }}' ? `
                <div class="section">
                    <div class="section-title">Treatment</div>
                    <div class="content-box">{{ $medicalRecord->treatment }}</div>
                </div>
                ` : ''}

                ${'{{ $medicalRecord->prescription }}' ? `
                <div class="section">
                    <div class="section-title">Prescription</div>
                    <div class="content-box prescription">{{ $medicalRecord->prescription }}</div>
                </div>
                ` : ''}

                ${'{{ $medicalRecord->vitals }}' && '{{ count(array_filter((array)$medicalRecord->vitals)) > 0 }}' ? `
                <div class="section">
                    <div class="section-title">Vital Signs</div>
                    <div class="vitals-grid">
                        ${'{{ $medicalRecord->vitals["blood_pressure"] ?? "" }}' ? `<div class="vital-item"><strong>BP:</strong> {{ $medicalRecord->vitals['blood_pressure'] }} mmHg</div>` : ''}
                        ${'{{ $medicalRecord->vitals["heart_rate"] ?? "" }}' ? `<div class="vital-item"><strong>Heart Rate:</strong> {{ $medicalRecord->vitals['heart_rate'] }} bpm</div>` : ''}
                        ${'{{ $medicalRecord->vitals["temperature"] ?? "" }}' ? `<div class="vital-item"><strong>Temp:</strong> {{ $medicalRecord->vitals['temperature'] }}°C</div>` : ''}
                        ${'{{ $medicalRecord->vitals["weight"] ?? "" }}' ? `<div class="vital-item"><strong>Weight:</strong> {{ $medicalRecord->vitals['weight'] }} kg</div>` : ''}
                    </div>
                </div>
                ` : ''}

                <div class="footer">
                    <p><strong>Confidential Medical Document</strong></p>
                    <p>This record was generated on ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</p>
                    <p>For official verification, please contact MediCare Hospital Medical Records Department</p>
                </div>

                <div class="no-print" style="text-align: center; margin-top: 30px;">
                    <button onclick="window.print()" style="padding: 10px 20px; margin: 5px;">Print This Page</button>
                    <button onclick="window.close()" style="padding: 10px 20px; margin: 5px;">Close Window</button>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection
@endsection