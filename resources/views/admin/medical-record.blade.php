@extends('layouts.admin')

@section('title', 'Medical Records')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Medical Records Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMedicalRecordModal">
                <i class="fas fa-plus me-2"></i>Create Medical Record
            </button> -->
        </div>
    </div>

    <!-- Alerts -->
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalRecords = $medicalRecords->total();
            $todayRecords = $medicalRecords->where('visit_date', today())->count();
            $thisWeekRecords = $medicalRecords->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $thisMonthRecords = $medicalRecords->whereBetween('visit_date', [now()->startOfMonth(), now()->endOfMonth()])->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Records
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Week
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisWeekRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisMonthRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Search & Filter
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter"></i> Toggle Filters
                    </button>
                </div>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('admin.medical-records') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by patient, doctor, or diagnosis..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select" id="patient_id" name="patient_id">
                                <option value="">All Patients</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="visit_date" class="form-label">Visit Date</label>
                            <input type="date" class="form-control" id="visit_date" name="visit_date" 
                                   value="{{ request('visit_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.medical-records') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Medical Records Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-medical me-2"></i>Medical Records
                <span class="badge bg-light text-primary ms-2">{{ $medicalRecords->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $medicalRecords->firstItem() ?? 0 }}-{{ $medicalRecords->lastItem() ?? 0 }} of {{ $medicalRecords->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($medicalRecords->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Visit Date</th>
                            <th class="border-0">Diagnosis</th>
                            <th class="border-0">Vital Signs</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecords as $record)
                        @php
                            $patientAge = $record->patient->date_of_birth ? \Carbon\Carbon::parse($record->patient->date_of_birth)->age : 'N/A';
                        @endphp
                        <tr>
                            <td>
                                <strong>#{{ $record->record_id ?? $record->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($record->patient->profile_image)
                                            <img src="{{ asset('storage/' . $record->patient->profile_image) }}" 
                                                 alt="{{ $record->patient->first_name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @else
                                            <!-- <div class="avatar-placeholder bg-primary">
                                                <i class="fas fa-user"></i>
                                            </div> -->
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $record->patient->first_name }} {{ $record->patient->last_name }}</strong>
                                        <small class="text-muted">
                                            {{ $patientAge }} yrs • {{ ucfirst($record->patient->gender) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($record->doctor->profile_image)
                                            <img src="{{ asset('storage/' . $record->doctor->profile_image) }}" 
                                                 alt="Dr. {{ $record->doctor->name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @else
                                            <!-- <div class="avatar-placeholder bg-success">
                                                <i class="fas fa-user-md"></i>
                                            </div> -->
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">Dr. {{ $record->doctor->name }}</strong>
                                        <small class="text-muted">{{ $record->doctor->specialization }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong class="d-block text-primary">{{ $record->visit_date->format('M d, Y') }}</strong>
                                    <small class="text-muted">
                                        {{ $record->visit_date->diffForHumans() }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark" data-bs-toggle="tooltip" title="{{ $record->diagnosis }}">
                                    {{ Str::limit($record->diagnosis, 40) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-center">
                                    @if($record->blood_pressure)
                                    <small class="d-block">
                                        <i class="fas fa-heartbeat text-danger me-1"></i>
                                        {{ $record->blood_pressure }}
                                    </small>
                                    @endif
                                    @if($record->bmi)
                                    <small class="d-block">
                                        <i class="fas fa-weight text-success me-1"></i>
                                        BMI: {{ $record->bmi }}
                                    </small>
                                    @endif
                                    @if($record->temperature)
                                    <small class="d-block">
                                        <i class="fas fa-thermometer-half text-warning me-1"></i>
                                        {{ $record->temperature }}°C
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewRecordModal{{ $record->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <!-- <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRecordModal{{ $record->id }}" 
                                            title="Edit Record">
                                        <i class="fas fa-edit"></i>
                                    </button> -->

                                    <!-- Print Button -->
                                    <!-- <button class="btn btn-primary" onclick="printMedicalRecord({{ $record->id }})" 
                                            title="Print Record">
                                        <i class="fas fa-print"></i>
                                    </button> -->

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRecordModal{{ $record->id }}" 
                                            title="Delete Record">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Record Modal -->
                        <div class="modal fade" id="viewRecordModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-file-medical me-2"></i>Medical Record - #{{ $record->record_id ?? $record->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Patient Information</h6>
                                                <p><strong>Name:</strong> {{ $record->patient->first_name }} {{ $record->patient->last_name }}</p>
                                                <p><strong>Age:</strong> {{ $patientAge }} years</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($record->patient->gender) }}</p>
                                                <p><strong>Blood Group:</strong> {{ $record->patient->blood_group ?? 'N/A' }}</p>
                                                <p><strong>Phone:</strong> {{ $record->patient->phone ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $record->patient->email }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Doctor Information</h6>
                                                <p><strong>Name:</strong> Dr. {{ $record->doctor->name }}</p>
                                                <p><strong>Specialization:</strong> {{ $record->doctor->specialization }}</p>
                                                <p><strong>Qualification:</strong> {{ $record->doctor->qualification ?? 'N/A' }}</p>
                                                <p><strong>Experience:</strong> {{ $record->doctor->experience_years ?? '0' }} years</p>
                                                <p><strong>Contact:</strong> {{ $record->doctor->email }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Medical Consultation</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Symptoms:</strong></p>
                                                        <div class="bg-light p-3 rounded mb-3">
                                                            {{ $record->symptoms ?? 'No symptoms recorded' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Diagnosis:</strong></p>
                                                        <div class="bg-light p-3 rounded mb-3">
                                                            {{ $record->diagnosis ?? 'No diagnosis recorded' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>Treatment:</strong></p>
                                                        <div class="bg-light p-3 rounded mb-3">
                                                            {{ $record->treatment ?? 'No treatment recorded' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($record->prescription)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><strong>Prescription:</strong></p>
                                                        <div class="bg-light p-3 rounded">
                                                            {{ $record->prescription }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Vital Signs</h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p><strong>Weight:</strong> {{ $record->weight ? $record->weight . ' kg' : 'N/A' }}</p>
                                                        <p><strong>Height:</strong> {{ $record->height ? $record->height . ' cm' : 'N/A' }}</p>
                                                        <p><strong>BMI:</strong> 
                                                            @if($record->bmi)
                                                                {{ $record->bmi }} 
                                                                <span class="badge 
                                                                    @if($record->bmi < 18.5) bg-info
                                                                    @elseif($record->bmi < 25) bg-success
                                                                    @elseif($record->bmi < 30) bg-warning
                                                                    @else bg-danger
                                                                    @endif">
                                                                    {{ $record->bmi_category ?? 'N/A' }}
                                                                </span>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><strong>Blood Pressure:</strong> {{ $record->blood_pressure ?: 'N/A' }}</p>
                                                        <p><strong>Heart Rate:</strong> {{ $record->heart_rate ? $record->heart_rate . ' bpm' : 'N/A' }}</p>
                                                        <p><strong>Temperature:</strong> {{ $record->temperature ? $record->temperature . ' °C' : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Additional Information</h6>
                                                <p><strong>Visit Date:</strong> {{ $record->visit_date->format('F d, Y') }}</p>
                                                <p><strong>Next Visit:</strong> {{ $record->next_visit_date ? $record->next_visit_date->format('F d, Y') : 'Not scheduled' }}</p>
                                                <p><strong>Record Created:</strong> {{ $record->created_at->format('F d, Y h:i A') }}</p>
                                                <p><strong>Last Updated:</strong> {{ $record->updated_at->format('F d, Y h:i A') }}</p>
                                            </div>
                                        </div>

                                        @if($record->notes)
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Doctor's Notes</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $record->notes }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Record Modal -->
                        <div class="modal fade" id="deleteRecordModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Are you sure you want to delete this medical record?</h5>
                                        <p class="mb-1"><strong>Record #{{ $record->record_id ?? $record->id }}</strong></p>
                                        <p class="text-muted">
                                            {{ $record->patient->first_name }} {{ $record->patient->last_name }} - 
                                            Dr. {{ $record->doctor->name }}
                                        </p>
                                        <p class="text-muted">
                                            {{ $record->visit_date->format('M d, Y') }}
                                        </p>
                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. All medical record data will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.medical-records.destroy', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Record
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $medicalRecords->firstItem() }} to {{ $medicalRecords->lastItem() }} of {{ $medicalRecords->total() }} entries
                </div>
                <div>
                    {{ $medicalRecords->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Medical Records Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'patient_id', 'doctor_id', 'visit_date']))
                        No medical records match your search criteria.
                    @else
                        No medical records are currently available.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'patient_id', 'doctor_id', 'visit_date']))
                <a href="{{ route('admin.medical-records') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addMedicalRecordModal">
                    <i class="fas fa-plus me-1"></i>Create First Record
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }

    .avatar-placeholder.bg-primary {
        background-color: #4e73df !important;
    }

    .avatar-placeholder.bg-success {
        background-color: #1cc88a !important;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
    }

    .card-header {
        font-weight: 600;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.35rem;
    }

    .card-header.bg-primary {
        border-bottom: none;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #e3e6f0;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
        border: 1px solid transparent;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .badge {
        font-size: 0.75em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
            margin-right: 0;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }

        // Reset form when modal is closed
        const addMedicalRecordModal = document.getElementById('addMedicalRecordModal');
        if (addMedicalRecordModal) {
            addMedicalRecordModal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    // Clear validation errors
                    const invalidInputs = form.querySelectorAll('.is-invalid');
                    invalidInputs.forEach(function(input) {
                        input.classList.remove('is-invalid');
                    });
                    const invalidFeedback = form.querySelectorAll('.invalid-feedback');
                    invalidFeedback.forEach(function(feedback) {
                        feedback.remove();
                    });
                }
            });
        }

        // Auto-show modal if there are validation errors
        @if($errors->any() && old('_token'))
            const addModal = new bootstrap.Modal(document.getElementById('addMedicalRecordModal'));
            addModal.show();
        @endif

        // Date validation
        const visitDateInputs = document.querySelectorAll('input[type="date"][name="visit_date"]');
        visitDateInputs.forEach(function(input) {
            const today = new Date().toISOString().split('T')[0];
            input.setAttribute('max', today);
            
            input.addEventListener('change', function(e) {
                const selectedDate = new Date(e.target.value);
                const today = new Date();
                if (selectedDate > today) {
                    alert('Visit date cannot be in the future');
                    e.target.value = today.toISOString().split('T')[0];
                }
            });
        });

        // Next visit date validation
        const nextVisitDateInputs = document.querySelectorAll('input[type="date"][name="next_visit_date"]');
        nextVisitDateInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const selectedDate = new Date(e.target.value);
                const today = new Date();
                if (selectedDate < today) {
                    alert('Next visit date cannot be in the past');
                    e.target.value = '';
                }
            });
        });

        // Blood pressure formatting
        const bloodPressureInputs = document.querySelectorAll('input[name="blood_pressure"]');
        bloodPressureInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d/]/g, '');
                e.target.value = value;
            });
        });

        // Auto-calculate BMI when weight and height are entered
        const weightInputs = document.querySelectorAll('input[name="weight"]');
        const heightInputs = document.querySelectorAll('input[name="height"]');
        
        function calculateBMI(weight, height) {
            if (weight && height && height > 0) {
                const heightInMeters = height / 100;
                return (weight / (heightInMeters * heightInMeters)).toFixed(1);
            }
            return null;
        }

        weightInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                const form = this.closest('form');
                const weight = parseFloat(this.value);
                const heightInput = form.querySelector('input[name="height"]');
                const height = parseFloat(heightInput?.value);
                
                const bmi = calculateBMI(weight, height);
                if (bmi) {
                    // You can add BMI display logic here if needed
                    console.log('BMI:', bmi);
                }
            });
        });

        heightInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                const form = this.closest('form');
                const height = parseFloat(this.value);
                const weightInput = form.querySelector('input[name="weight"]');
                const weight = parseFloat(weightInput?.value);
                
                const bmi = calculateBMI(weight, height);
                if (bmi) {
                    // You can add BMI display logic here if needed
                    console.log('BMI:', bmi);
                }
            });
        });
    });

    // Print medical record function
    function printMedicalRecord(recordId) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Medical Record #${recordId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                        .section { margin-bottom: 20px; }
                        .section h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                        .patient-info, .doctor-info { display: inline-block; width: 48%; vertical-align: top; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Medical Record</h1>
                        <p>Record ID: #${recordId}</p>
                        <p>Printed on: ${new Date().toLocaleDateString()}</p>
                    </div>
                    <p>This feature will display the complete medical record in a printable format.</p>
                    <div class="no-print">
                        <button onclick="window.print()">Print</button>
                        <button onclick="window.close()">Close</button>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection