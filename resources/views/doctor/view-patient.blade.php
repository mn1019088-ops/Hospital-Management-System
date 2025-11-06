@extends('layouts.doctor')

@section('title', 'My Patients')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Patients</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $doctorId = Auth::guard('doctor')->id();
        $patientStats = [
            'total_patients' => $patients->total(),
            'male_patients' => $patients->where('gender', 'male')->count(),
            'female_patients' => $patients->where('gender', 'female')->count(),
            'new_this_month' => \App\Models\Patient::whereHas('appointments', function($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId)
                  ->whereMonth('created_at', now()->month);
            })->count()
        ];
    @endphp

    @foreach([
        'total_patients' => ['Total Patients', 'primary', 'fas fa-users'],
        'male_patients' => ['Male Patients', 'info', 'fas fa-male'],
        'female_patients' => ['Female Patients', 'warning', 'fas fa-female'],
        'new_this_month' => ['New This Month', 'success', 'fas fa-user-plus']
    ] as $key => [$title, $color, $icon])
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-{{ $color }} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                            {{ $title }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patientStats[$key] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="{{ $icon }} fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
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
            <form action="{{ route('doctor.patients') }}" method="GET">
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
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('doctor.patients') }}" class="btn btn-secondary">Clear Filters</a>
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

    <div class="card-body">
        @if($patients->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Patient Info</th>
                        <th>Age & Gender</th>
                        <th>Contact</th>
                        <th>Blood Group</th>
                        <th>Last Visit</th>
                        <th>Total Visits</th>
                        <th class="text-center">Actions</th>
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
                                        <small class="text-muted">ID: {{ $patient->patient_id }}</small><br>
                                        <small class="text-muted">{{ $patient->email }}</small>
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
                                    @if($patient->address)
                                    <i class="fas fa-map-marker-alt text-warning me-1"></i>
                                    <span data-bs-toggle="tooltip" title="{{ $patient->address }}">
                                        {{ \Illuminate\Support\Str::limit($patient->address, 20) }}
                                    </span>
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
                                    $lastAppointment = $patient->appointments()
                                        ->where('doctor_id', $doctorId)
                                        ->latest()
                                        ->first();
                                @endphp
                                @if($lastAppointment)
                                    <div class="text-nowrap">
                                        <strong>{{ $lastAppointment->appointment_date->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('h:i A') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $totalVisits = $patient->appointments()
                                        ->where('doctor_id', $doctorId)
                                        ->count();
                                @endphp
                                <span class="badge bg-secondary">{{ $totalVisits }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Fixed route names -->
                                    <a href="{{ route('doctor.patients.show', $patient->id) }}" 
                                       class="btn btn-primary" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" 
                                       class="btn btn-success" title="Add Medical Record">
                                        <i class="fas fa-file-medical"></i>
                                    </a>
                                    <a href="{{ route('doctor.patients.medical-records', $patient->id) }}" 
                                       class="btn btn-warning" title="View Medical History">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
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
            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-1"></i>Clear Filters
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
        
        // Show filters if any search parameters are present
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }
    });
</script>
@endsection