@extends('layouts.doctor')

@section('title', 'Medical History - ' . $patient->first_name)

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-history me-2"></i>Medical History - {{ $patient->first_name }} {{ $patient->last_name }}
    </h1>
    <div>
        <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-primary me-2">
            <i class="fas fa-user me-2"></i>Patient Profile
        </a>
        <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" class="btn btn-success">
            <i class="fas fa-file-medical me-2"></i>New Record
        </a>
    </div>
</div>

<!-- Patient Summary -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($patient->profile_image)
                            <img src="{{ asset('storage/' . $patient->profile_image) }}" 
                                 alt="{{ $patient->first_name }}" 
                                 class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                        <h5 class="mt-2 mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                        <small class="text-muted">ID: {{ $patient->patient_id }}</small>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Age:</strong> {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' years' : 'N/A' }}<br>
                                <strong>Gender:</strong> {{ ucfirst($patient->gender) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Blood Group:</strong> 
                                @if($patient->blood_group)
                                    <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                                <br>
                                <strong>Phone:</strong> {{ $patient->phone ?? 'N/A' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Total Records:</strong> 
                                <span class="badge bg-primary">{{ $medicalRecords->count() }}</span><br>
                                <strong>Last Visit:</strong> 
                                @if($medicalRecords->count() > 0)
                                    {{ $medicalRecords->first()->visit_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($medicalRecords->count() > 0)
<!-- Medical Records Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card border-light shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-medical me-2"></i>Medical Records Timeline
                    <span class="badge bg-primary ms-2">{{ $medicalRecords->count() }} Records</span>
                </h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-sort me-1"></i>Sort By
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?sort=latest">Latest First</a></li>
                        <li><a class="dropdown-item" href="?sort=oldest">Oldest First</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($medicalRecords as $record)
                    <div class="timeline-item mb-4">
                        <div class="card border-info shadow-sm">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $record->visit_date->format('F d, Y') }}
                                    </strong>
                                    <small class="ms-2">({{ $record->visit_date->diffForHumans() }})</small>
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark me-2">Record #{{ $record->record_id }}</span>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('doctor.medical-records.show', $record->id) }}" 
                                           class="btn btn-light" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('doctor.medical-records.edit', $record->id) }}" 
                                           class="btn btn-light" title="Edit Record">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Vital Signs -->
                                    <div class="col-md-4 border-end">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-heartbeat me-2"></i>Vital Signs
                                        </h6>
                                        <div class="row">
                                            @if($record->weight)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Weight</small><br>
                                                <strong>{{ $record->weight }} kg</strong>
                                            </div>
                                            @endif
                                            @if($record->height)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Height</small><br>
                                                <strong>{{ $record->height }} cm</strong>
                                            </div>
                                            @endif
                                            @if($record->bmi)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">BMI</small><br>
                                                <strong>{{ $record->bmi }}</strong>
                                                <small class="text-muted">({{ $record->bmi_category }})</small>
                                            </div>
                                            @endif
                                            @if($record->blood_pressure)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">BP</small><br>
                                                <strong>{{ $record->blood_pressure }}</strong>
                                            </div>
                                            @endif
                                            @if($record->temperature)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Temp</small><br>
                                                <strong>{{ $record->temperature }}°C</strong>
                                            </div>
                                            @endif
                                            @if($record->pulse_rate)
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Pulse</small><br>
                                                <strong>{{ $record->pulse_rate }} bpm</strong>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Medical Information -->
                                    <div class="col-md-8">
                                        <div class="row">
                                            @if($record->symptoms)
                                            <div class="col-12 mb-3">
                                                <h6 class="text-primary">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Symptoms
                                                </h6>
                                                <p class="mb-0">{{ $record->symptoms }}</p>
                                            </div>
                                            @endif

                                            @if($record->diagnosis)
                                            <div class="col-12 mb-3">
                                                <h6 class="text-primary">
                                                    <i class="fas fa-stethoscope me-2"></i>Diagnosis
                                                </h6>
                                                <p class="mb-0">{{ $record->diagnosis }}</p>
                                            </div>
                                            @endif

                                            @if($record->treatment)
                                            <div class="col-12 mb-3">
                                                <h6 class="text-primary">
                                                    <i class="fas fa-pills me-2"></i>Treatment
                                                </h6>
                                                <p class="mb-0">{{ $record->treatment }}</p>
                                            </div>
                                            @endif

                                            @if($record->prescription)
                                            <div class="col-12">
                                                <h6 class="text-primary">
                                                    <i class="fas fa-prescription me-2"></i>Prescription
                                                </h6>
                                                <p class="mb-0">{{ $record->prescription }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Notes -->
                                @if($record->notes)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-light border">
                                            <h6 class="text-secondary mb-2">
                                                <i class="fas fa-sticky-note me-2"></i>Additional Notes
                                            </h6>
                                            <p class="mb-0">{{ $record->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Recorded By -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <i class="fas fa-user-md me-1"></i>
                                            Recorded by Dr. {{ $record->doctor->name ?? 'N/A' }}
                                            on {{ $record->created_at->format('M d, Y \\a\\t h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- Empty State -->
<div class="row">
    <div class="col-12">
        <div class="card border-light shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Medical Records Found</h4>
                <p class="text-muted mb-4">No medical records have been created for this patient yet.</p>
                <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" 
                   class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Create First Medical Record
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Back to Top Button -->
<div class="text-center mt-4">
    <a href="#" class="btn btn-outline-primary" id="backToTop">
        <i class="fas fa-arrow-up me-2"></i>Back to Top
    </a>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item:first-child:before {
        top: 20px;
    }
    .timeline-item:last-child:before {
        bottom: calc(100% - 20px);
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back to top functionality
        const backToTop = document.getElementById('backToTop');
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Show/hide back to top button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Initially hide the back to top button
        backToTop.style.display = 'none';

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