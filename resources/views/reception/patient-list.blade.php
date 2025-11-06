@extends('layouts.reception')

@section('title', 'Patient List')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Patient Management</h1>
    </div>

    <!-- Toastr Notifications -->
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalPatients = $patients->total();
            $activePatients = $patients->where('is_active', true)->count();
            $todayPatients = \App\Models\Patient::whereDate('created_at', today())->count();
            $thisWeekPatients = \App\Models\Patient::where('created_at', '>=', now()->subWeek())->count();
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
                                Registered Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayPatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                This Week
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisWeekPatients }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
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
                <form action="{{ route('reception.patients.list') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Search by name, email, phone, or ID..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
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
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('reception.patients.list') }}" class="btn btn-secondary">
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
                            <th class="border-0">#</th>
                            <th class="border-0">Patient Info</th>
                            <th class="border-0">Age & Gender</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Blood Group</th>
                            <th class="border-0">Last Visit</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        <tr>
                            <td>{{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}</td>
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
                                        <small class="text-muted">ID: {{ $patient->patient_id ?? 'PAT' . $patient->id }}</small><br>
                                        <small class="text-muted">{{ $patient->email ?? 'No email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' years' : 'N/A' }}</strong><br>
                                    <span class="badge bg-info">{{ ucfirst($patient->gender) }}</span>
                                </div>
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-phone text-success me-1"></i>{{ $patient->phone ?? 'N/A' }}<br>
                                    @if($patient->emergency_contact)
                                    <i class="fas fa-phone-alt text-warning me-1"></i>{{ $patient->emergency_contact }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                @if($patient->blood_group)
                                    <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $lastAppointment = $patient->appointments()->latest()->first();
                                @endphp
                                @if($lastAppointment)
                                    <div class="text-nowrap">
                                        <strong>{{ $lastAppointment->appointment_date->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">Dr. {{ $lastAppointment->doctor->name ?? 'N/A' }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('reception.patients.show', $patient->id) }}" 
                                       class="btn btn-primary" title="View Profile" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}" 
                                            title="Edit Patient" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Book Appointment Button - Using URL helper instead of route -->
                                    <a href="{{ url('/reception/appointments/create?patient_id=' . $patient->id) }}" 
                                       class="btn btn-success" title="Book Appointment" data-bs-toggle="tooltip">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>
                                    <!-- Status Toggle Button - Using URL helper -->
                                    <form action="{{ url('/reception/patients/' . $patient->id . '/toggle-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn {{ $patient->is_active ? 'btn-danger' : 'btn-success' }}" 
                                                title="{{ $patient->is_active ? 'Deactivate Patient' : 'Activate Patient' }}"
                                                onclick="return confirm('Are you sure you want to {{ $patient->is_active ? 'deactivate' : 'activate' }} this patient?')"
                                                data-bs-toggle="tooltip">
                                            <i class="fas {{ $patient->is_active ? 'fa-user-times' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Patient Modal -->
                        <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Using URL helper for the form action -->
                                    <form action="{{ url('/reception/patients/' . $patient->id) }}" method="POST" id="editForm{{ $patient->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit me-2"></i>Edit Patient - {{ $patient->first_name }} {{ $patient->last_name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">First Name</label>
                                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                                               value="{{ old('first_name', $patient->first_name) }}" required minlength="2" maxlength="50">
                                                        @error('first_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                                               value="{{ old('last_name', $patient->last_name) }}" required minlength="1" maxlength="50">
                                                        @error('last_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                               value="{{ old('email', $patient->email) }}" placeholder="email@example.com">
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">Phone Number</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">+91</span>
                                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                                   value="{{ old('phone', $patient->phone) }}" required 
                                                                   pattern="[6-9]\d{9}" title="Enter a valid 10-digit Indian mobile number">
                                                        </div>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">Date of Birth</label>
                                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                               value="{{ old('date_of_birth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}" required 
                                                               max="{{ date('Y-m-d') }}">
                                                        @error('date_of_birth')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">Gender</label>
                                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                        @error('gender')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Blood Group</label>
                                                        <select name="blood_group" class="form-control @error('blood_group') is-invalid @enderror">
                                                            <option value="">Select Blood Group</option>
                                                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                                                <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('blood_group')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Emergency Contact</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">+91</span>
                                                            <input type="tel" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                                                   value="{{ old('emergency_contact', $patient->emergency_contact) }}" 
                                                                   pattern="[6-9]\d{9}" title="Enter a valid 10-digit Indian mobile number">
                                                        </div>
                                                        @error('emergency_contact')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Medical History / Allergies</label>
                                                <textarea name="medical_history" class="form-control @error('medical_history') is-invalid @enderror" 
                                                          rows="3" placeholder="Enter any known medical conditions or allergies">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                                @error('medical_history')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                                          rows="2" placeholder="Enter full address">{{ old('address', $patient->address) }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-2"></i>Update Patient
                                            </button>
                                        </div>
                                    </form>
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
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Patients Found</h4>
                <p class="text-muted">No patients match your search criteria.</p>
                <a href="{{ route('reception.patients.list') }}" class="btn btn-primary me-2">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                <a href="{{ route('reception.patient-add') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Add New Patient
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }

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

    /* Statistics Cards */
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

    .card-body {
        padding: 1.25rem;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
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

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.075);
    }

    /* Button Styles */
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

    /* Badge Styles */
    .badge {
        font-size: 0.75em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    /* Modal Styles */
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

    /* Form Styles */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #d1d3e2;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 0.35rem;
        margin-bottom: 1rem;
    }

    .alert-dismissible .btn-close {
        padding: 0.75rem;
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        color: #4e73df;
        border: 1px solid #e3e6f0;
    }

    .page-link:hover {
        color: #2e59d9;
        background-color: #eaecf4;
        border-color: #e3e6f0;
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .btn-group .btn {
            margin-right: 0;
        }
        
        .d-flex.justify-content-between.align-items-center.flex-wrap {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
    }

    /* Loading States */
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }

    /* Animation for alerts */
    .alert {
        transition: all 0.3s ease-in-out;
    }

    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Status badges */
    .badge.bg-success {
        background-color: #1cc88a !important;
    }

    .badge.bg-danger {
        background-color: #e74a3b !important;
    }

    .badge.bg-info {
        background-color: #36b9cc !important;
    }

    .badge.bg-warning {
        background-color: #f6c23e !important;
        color: #000 !important;
    }

    .badge.bg-secondary {
        background-color: #858796 !important;
    }

    /* Tooltip customization */
    .tooltip {
        font-size: 0.75rem;
    }

    /* Filter section improvements */
    #filterCollapse .card-body {
        background-color: #f8f9fc;
    }

    /* Statistics card text colors */
    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .text-gray-300 {
        color: #dddfeb !important;
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

        // Form validation for edit forms
        document.querySelectorAll('form[id^="editForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
            });
        });

        // Phone number validation
        document.querySelectorAll('input[name="phone"], input[name="emergency_contact"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                e.target.value = value;
                
                // Validate phone format
                if (value && !/^[6-9]\d{9}$/.test(value)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Date validation - ensure date is not in future
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.setAttribute('max', new Date().toISOString().split('T')[0]);
        });

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        // Show filters if any search parameters are present
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }

        // Enhanced search functionality
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        this.form.submit();
                    }
                }, 800);
            });
        }
    });
</script>
@endsection