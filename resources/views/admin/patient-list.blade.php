@extends('layouts.admin')

@section('title', 'Patient Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Patient Management</h1>
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
            $totalPatients = $patients->total();
            $activePatients = $patients->where('is_active', true)->count();
            $malePatients = $patients->where('gender', 'male')->count();
            $femalePatients = $patients->where('gender', 'female')->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Patients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Active Patients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activePatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Male Patients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $malePatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-male fa-2x text-gray-300"></i>
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
                                Female Patients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $femalePatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-female fa-2x text-gray-300"></i>
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
                <form action="{{ route('admin.patients') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by name, email, or patient ID..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-select" id="blood_group" name="blood_group">
                                <option value="">All Blood Groups</option>
                                <option value="A+" {{ request('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ request('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ request('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ request('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ request('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ request('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ request('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ request('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.patients') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Patients Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i>Patient List
                <span class="badge bg-light text-primary ms-2">{{ $patients->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $patients->firstItem() ?? 0 }}-{{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($patients->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Personal Info</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Medical</th>
                            <th class="border-0">Last Visit</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        @php
                            $age = $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A';
                            $lastAppointment = $patient->appointments()->latest()->first();
                        @endphp
                        <tr>
                            <td>
                                <strong>#{{ $patient->patient_id ?? $patient->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($patient->profile_image)
                                            <img src="{{ asset('storage/' . $patient->profile_image) }}" 
                                                 alt="{{ $patient->first_name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                                        <small class="text-muted">{{ $patient->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="d-block"><strong>Age:</strong> {{ $age }} years</small>
                                    <small class="d-block">
                                        <strong>Gender:</strong> 
                                        <span class="badge {{ $patient->gender == 'male' ? 'bg-primary' : ($patient->gender == 'female' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($patient->gender) }}
                                        </span>
                                    </small>
                                    <small class="d-block"><strong>DOB:</strong> {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="d-block"><i class="fas fa-phone me-1 text-muted"></i>{{ $patient->phone ?? 'N/A' }}</small>
                                    <small class="d-block"><i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ Str::limit($patient->address, 30) ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($patient->blood_group)
                                    <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                @if($lastAppointment)
                                    <div class="text-nowrap">
                                        <small class="d-block text-success">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            {{ $lastAppointment->appointment_date->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('h:i A') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewPatientModal{{ $patient->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.patients.toggle-status', $patient->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $patient->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                title="{{ $patient->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $patient->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePatientModal{{ $patient->id }}" 
                                            title="Delete Patient">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Patient Modal -->
                        <div class="modal fade" id="viewPatientModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user me-2"></i>Patient Details - {{ $patient->first_name }} {{ $patient->last_name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Personal Information</h6>
                                                <p><strong>Patient ID:</strong> {{ $patient->patient_id ?? 'N/A' }}</p>
                                                <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                                                <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') : 'N/A' }}</p>
                                                <p><strong>Age:</strong> {{ $age }} years</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Contact Information</h6>
                                                <p><strong>Email:</strong> {{ $patient->email }}</p>
                                                <p><strong>Phone:</strong> {{ $patient->phone ?? 'N/A' }}</p>
                                                <p><strong>Address:</strong> {{ $patient->address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Medical Information</h6>
                                                <p><strong>Blood Group:</strong> {{ $patient->blood_group ?? 'N/A' }}</p>
                                                <p><strong>Allergies:</strong> {{ $patient->allergies ?? 'None recorded' }}</p>
                                                <p><strong>Medical History:</strong> {{ $patient->medical_history ?? 'None recorded' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Account Information</h6>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </p>
                                                <p><strong>Registered:</strong> {{ $patient->created_at->format('F d, Y') }}</p>
                                                <p><strong>Last Updated:</strong> {{ $patient->updated_at->format('F d, Y') }}</p>
                                            </div>
                                        </div>

                                        @if($lastAppointment)
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Last Appointment</h6>
                                                <div class="bg-light p-3 rounded">
                                                    <p><strong>Date:</strong> {{ $lastAppointment->appointment_date->format('F d, Y') }}</p>
                                                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('h:i A') }}</p>
                                                    <p><strong>Doctor:</strong> Dr. {{ $lastAppointment->doctor->name ?? 'N/A' }}</p>
                                                    <p><strong>Status:</strong> 
                                                        <span class="badge 
                                                            @if($lastAppointment->status == 'scheduled') bg-warning text-dark
                                                            @elseif($lastAppointment->status == 'confirmed') bg-primary
                                                            @elseif($lastAppointment->status == 'completed') bg-success
                                                            @elseif($lastAppointment->status == 'cancelled') bg-danger
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst($lastAppointment->status) }}
                                                        </span>
                                                    </p>
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

                        <!-- Delete Patient Modal -->
                        <div class="modal fade" id="deletePatientModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Are you sure you want to delete this patient?</h5>
                                        <p class="mb-1"><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></p>
                                        <p class="text-muted">{{ $patient->patient_id ?? $patient->id }} • {{ $patient->email }}</p>
                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. All associated data will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Patient
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
                    Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} entries
                </div>
                <div>
                    {{ $patients->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-user-injured fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Patients Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'gender', 'status', 'blood_group']))
                        No patients match your search criteria.
                    @else
                        No patients are currently registered in the system.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'gender', 'status', 'blood_group']))
                <a href="{{ route('admin.patients') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="fas fa-plus me-1"></i>Add First Patient
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
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
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
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
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
        const addPatientModal = document.getElementById('addPatientModal');
        if (addPatientModal) {
            addPatientModal.addEventListener('hidden.bs.modal', function () {
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
            const addModal = new bootstrap.Modal(document.getElementById('addPatientModal'));
            addModal.show();
        @endif

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strengthIndicator = document.getElementById('password-strength');
                
                if (!strengthIndicator) {
                    strengthIndicator = document.createElement('div');
                    strengthIndicator.id = 'password-strength';
                    strengthIndicator.className = 'mt-1 small';
                    this.parentNode.appendChild(strengthIndicator);
                }
                
                let strength = 'Weak';
                let color = 'text-danger';
                
                if (password.length >= 8) {
                    strength = 'Medium';
                    color = 'text-warning';
                }
                if (password.length >= 12 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
                    strength = 'Strong';
                    color = 'text-success';
                }
                
                strengthIndicator.innerHTML = `Password strength: <span class="${color}">${strength}</span>`;
            });
        }

        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[type="text"][name="phone"]');
        phoneInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                
                if (value.length > 6) {
                    value = value.substring(0, 6) + '-' + value.substring(6);
                }
                if (value.length > 3) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                }
                
                e.target.value = value;
            });
        });

        // Date of birth validation
        const dobInputs = document.querySelectorAll('input[type="date"][name="date_of_birth"]');
        dobInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const selectedDate = new Date(e.target.value);
                const today = new Date();
                if (selectedDate > today) {
                    alert('Date of birth cannot be in the future');
                    e.target.value = '';
                }
            });
        });
    });
</script>
@endsection