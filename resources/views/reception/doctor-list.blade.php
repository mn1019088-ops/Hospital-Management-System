@extends('layouts.reception')

@section('title', 'Doctor Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Doctor Management</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalDoctors = $doctors->total();
            $activeDoctors = $doctors->where('is_active', true)->count();
            $specializations = $doctors->pluck('specialization')->unique()->filter()->values();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Doctors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDoctors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
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
                                Active Doctors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeDoctors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Specializations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $specializations->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
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
                                Inactive Doctors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDoctors - $activeDoctors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Search & Filter
            </h5>
            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-chevron-down"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('reception.doctors') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by name or specialization..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <select class="form-select" id="specialization" name="specialization">
                                <option value="">All Specializations</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                        {{ $spec }}
                                    </option>
                                @endforeach
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
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            @if(request()->anyFilled(['search', 'specialization', 'status']))
                            <a href="{{ route('reception.doctors') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-refresh me-1"></i>Clear Filters
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Doctors Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-md me-2"></i>Doctor List
                <span class="badge bg-light text-primary ms-2">{{ $doctors->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    Showing {{ $doctors->firstItem() ?? 0 }}-{{ $doctors->lastItem() ?? 0 }} of {{ $doctors->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($doctors->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Specialization</th>
                            <th class="border-0">Qualification</th>
                            <th class="border-0">Experience</th>
                            <th class="border-0">Availability</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        @php
                            $isAvailable = $doctor->is_active;
                            $currentTime = now();
                            $availableFrom = $doctor->available_from ? \Carbon\Carbon::parse($doctor->available_from) : null;
                            $availableTo = $doctor->available_to ? \Carbon\Carbon::parse($doctor->available_to) : null;
                            $isWithinHours = $availableFrom && $availableTo ? 
                                $currentTime->between($availableFrom, $availableTo) : true;
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($doctor->profile_image && file_exists(public_path('storage/' . $doctor->profile_image)))
                                            <img src="{{ asset('storage/' . $doctor->profile_image) }}" 
                                                 alt="Dr. {{ $doctor->name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">Dr. {{ $doctor->name }}</strong>
                                        <small class="text-muted">{{ $doctor->doctor_id ?? 'ID N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $doctor->specialization }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $doctor->qualification ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $doctor->experience_years ?? '0' }}</strong> years
                            </td>
                            <td>
                                @if($doctor->available_from && $doctor->available_to)
                                    <div class="text-nowrap">
                                        <small class="d-block text-success">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($doctor->available_from)->format('h:i A') }}
                                        </small>
                                        <small class="d-block text-danger">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($doctor->available_to)->format('h:i A') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="badge bg-secondary">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($doctor->is_active)
                                    <span class="badge {{ $isAvailable && $isWithinHours ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $isAvailable && $isWithinHours ? 'Available' : 'Busy' }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#viewDoctorModal{{ $doctor->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Doctor Modal -->
                        <div class="modal fade" id="viewDoctorModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user-md me-2"></i>Doctor Details - Dr. {{ $doctor->name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3 text-primary">Professional Information</h6>
                                                <div class="mb-2">
                                                    <strong>Name:</strong> Dr. {{ $doctor->name }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Doctor ID:</strong> {{ $doctor->doctor_id ?? 'N/A' }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Specialization:</strong> 
                                                    <span class="badge bg-info text-dark">{{ $doctor->specialization }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Qualification:</strong> {{ $doctor->qualification ?? 'N/A' }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Experience:</strong> {{ $doctor->experience_years ?? '0' }} years
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3 text-primary">Contact & Availability</h6>
                                                <div class="mb-2">
                                                    <strong>Email:</strong> {{ $doctor->email }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Phone:</strong> {{ $doctor->phone ?? 'N/A' }}
                                                </div>
                                                @if($doctor->available_from && $doctor->available_to)
                                                <div class="mb-2">
                                                    <strong>Available Hours:</strong> 
                                                    {{ \Carbon\Carbon::parse($doctor->available_from)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($doctor->available_to)->format('h:i A') }}
                                                </div>
                                                @endif
                                                <div class="mb-2">
                                                    <strong>Status:</strong> 
                                                    <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Current Availability:</strong> 
                                                    <span class="badge {{ $isAvailable && $isWithinHours ? 'bg-success' : 'bg-warning text-dark' }}">
                                                        {{ $isAvailable && $isWithinHours ? 'Available' : 'Busy' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($doctor->bio)
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2 mb-3 text-primary">Professional Bio</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $doctor->bio }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($doctor->expertise)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2 mb-3 text-primary">Areas of Expertise</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $doctor->expertise }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($doctors->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} entries
                </div>
                <div>
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Doctors Found</h4>
                <p class="text-muted mb-4">
                    @if(request()->anyFilled(['search', 'specialization', 'status']))
                        No doctors match your search criteria. Try adjusting your filters.
                    @else
                        No doctors are currently registered in the system.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'specialization', 'status']))
                <a href="{{ route('reception.doctors') }}" class="btn btn-primary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        border-radius: 0.5rem;
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
        background-color: #f8f9fc;
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6e707e;
        border-bottom: 1px solid #e3e6f0;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #e3e6f0;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
        border: 1px solid #e3e6f0;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-group-sm > .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
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
        border-bottom: 1px solid #e3e6f0;
        padding: 1.25rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 1.25rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.075);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-close alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });

        // Filter collapse state based on URL parameters
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.toString() === '') {
            const bsCollapse = new bootstrap.Collapse(filterCollapse, { toggle: false });
        }



        

        // Add loading state to filter button
        const filterForm = document.querySelector('form');
        const filterButton = filterForm.querySelector('button[type="submit"]');
        
        filterForm.addEventListener('submit', function() {
            filterButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Filtering...';
            filterButton.disabled = true;
        });
    });
</script>
@endsection