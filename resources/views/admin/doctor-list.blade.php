@extends('layouts.admin')

@section('title', 'Doctor Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Doctor Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                <i class="fas fa-plus me-2"></i>Add New Doctor
            </button>
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
                <form action="{{ route('admin.doctors') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by name, email, or specialization..." 
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
                        <div class="col-md-3">
                            <label for="experience" class="form-label">Experience (Years)</label>
                            <select class="form-select" id="experience" name="experience">
                                <option value="">All Experience</option>
                                <option value="0-5" {{ request('experience') == '0-5' ? 'selected' : '' }}>0-5 Years</option>
                                <option value="6-10" {{ request('experience') == '6-10' ? 'selected' : '' }}>6-10 Years</option>
                                <option value="11-20" {{ request('experience') == '11-20' ? 'selected' : '' }}>11-20 Years</option>
                                <option value="20+" {{ request('experience') == '20+' ? 'selected' : '' }}>20+ Years</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.doctors') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
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
                <span class="text-light me-3">Showing {{ $doctors->firstItem() ?? 0 }}-{{ $doctors->lastItem() ?? 0 }} of {{ $doctors->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($doctors->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Specialization</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Experience</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        <tr>
                            <td>
                                <strong>#{{ $doctor->doctor_id ?? $doctor->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($doctor->profile_image)
                                            <img src="{{ asset('storage/' . $doctor->profile_image) }}" 
                                                 alt="Dr. {{ $doctor->name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">Dr. {{ $doctor->name }}</strong>
                                        <small class="text-muted">{{ $doctor->qualification ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $doctor->specialization }}</span>
                            </td>
                            <td>
                                <div>
                                    <small class="d-block"><i class="fas fa-envelope me-1 text-muted"></i>{{ $doctor->email }}</small>
                                    <small class="d-block"><i class="fas fa-phone me-1 text-muted"></i>{{ $doctor->phone ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $doctor->experience_years ?? '0' }}</strong> years
                            </td>
                            <td>
                                <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewDoctorModal{{ $doctor->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doctor->id }}" 
                                            title="Edit Doctor">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.doctors.toggle-status', $doctor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $doctor->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                title="{{ $doctor->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $doctor->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal{{ $doctor->id }}" 
                                            title="Delete Doctor">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Doctor Modal -->
                        <div class="modal fade" id="viewDoctorModal{{ $doctor->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user-md me-2"></i>Doctor Details - Dr. {{ $doctor->name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Professional Information</h6>
                                                <p><strong>Doctor ID:</strong> {{ $doctor->doctor_id ?? 'N/A' }}</p>
                                                <p><strong>Name:</strong> Dr. {{ $doctor->name }}</p>
                                                <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
                                                <p><strong>Qualification:</strong> {{ $doctor->qualification ?? 'N/A' }}</p>
                                                <p><strong>Experience:</strong> {{ $doctor->experience_years ?? '0' }} years</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Contact Information</h6>
                                                <p><strong>Email:</strong> {{ $doctor->email }}</p>
                                                <p><strong>Phone:</strong> {{ $doctor->phone ?? 'N/A' }}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </p>
                                                @if($doctor->available_from && $doctor->available_to)
                                                <p><strong>Available Hours:</strong> 
                                                    {{ \Carbon\Carbon::parse($doctor->available_from)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($doctor->available_to)->format('h:i A') }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($doctor->bio)
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Professional Bio</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $doctor->bio }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($doctor->expertise)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Areas of Expertise</h6>
                                                <div class="bg-light p-3 rounded" style="white-space: pre-line;">{{ $doctor->expertise }}</div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Account Information</h6>
                                                <p><strong>Created:</strong> {{ $doctor->created_at->format('F d, Y') }}</p>
                                                <p><strong>Last Updated:</strong> {{ $doctor->updated_at->format('F d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Doctor Modal -->
                        <div class="modal fade" id="editDoctorModal{{ $doctor->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">Edit Doctor - Dr. {{ $doctor->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_name_{{ $doctor->id }}" class="form-label">Full Name *</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                               id="edit_name_{{ $doctor->id }}" name="name" value="{{ old('name', $doctor->name) }}" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_email_{{ $doctor->id }}" class="form-label">Email Address *</label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                               id="edit_email_{{ $doctor->id }}" name="email" value="{{ old('email', $doctor->email) }}" required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_phone_{{ $doctor->id }}" class="form-label">Phone Number *</label>
                                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror phone-input" 
                                                               id="edit_phone_{{ $doctor->id }}" name="phone" value="{{ old('phone', $doctor->phone) }}" 
                                                               pattern="[0-9]{10}" maxlength="10" required>
                                                        <div class="form-text">Enter 10-digit phone number without spaces or dashes</div>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_qualification_{{ $doctor->id }}" class="form-label">Qualification *</label>
                                                        <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                                                               id="edit_qualification_{{ $doctor->id }}" name="qualification" value="{{ old('qualification', $doctor->qualification) }}" required>
                                                        @error('qualification')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_specialization_{{ $doctor->id }}" class="form-label">Specialization *</label>
                                                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                                               id="edit_specialization_{{ $doctor->id }}" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" required>
                                                        @error('specialization')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_experience_years_{{ $doctor->id }}" class="form-label">Experience (Years) *</label>
                                                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                                               id="edit_experience_years_{{ $doctor->id }}" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}" 
                                                               min="0" max="50" required>
                                                        @error('experience_years')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_license_number_{{ $doctor->id }}" class="form-label">License Number</label>
                                                        <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                                               id="edit_license_number_{{ $doctor->id }}" name="license_number" value="{{ old('license_number', $doctor->license_number) }}">
                                                        @error('license_number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_is_active_{{ $doctor->id }}" class="form-label">Status</label>
                                                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                                                id="edit_is_active_{{ $doctor->id }}" name="is_active">
                                                            <option value="1" {{ old('is_active', $doctor->is_active) ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !old('is_active', $doctor->is_active) ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                        @error('is_active')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit_bio_{{ $doctor->id }}" class="form-label">Biography</label>
                                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                                          id="edit_bio_{{ $doctor->id }}" name="bio" rows="3">{{ old('bio', $doctor->bio) }}</textarea>
                                                @error('bio')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit_expertise_{{ $doctor->id }}" class="form-label">Areas of Expertise</label>
                                                <textarea class="form-control @error('expertise') is-invalid @enderror" 
                                                          id="edit_expertise_{{ $doctor->id }}" name="expertise" rows="2" 
                                                          placeholder="List areas of expertise separated by commas">{{ old('expertise', $doctor->expertise) }}</textarea>
                                                @error('expertise')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_available_from_{{ $doctor->id }}" class="form-label">Available From</label>
                                                        <input type="time" class="form-control @error('available_from') is-invalid @enderror" 
                                                               id="edit_available_from_{{ $doctor->id }}" name="available_from" value="{{ old('available_from', $doctor->available_from) }}">
                                                        @error('available_from')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_available_to_{{ $doctor->id }}" class="form-label">Available To</label>
                                                        <input type="time" class="form-control @error('available_to') is-invalid @enderror" 
                                                               id="edit_available_to_{{ $doctor->id }}" name="available_to" value="{{ old('available_to', $doctor->available_to) }}">
                                                        @error('available_to')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_password_{{ $doctor->id }}" class="form-label">Password (Leave blank to keep current)</label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                               id="edit_password_{{ $doctor->id }}" name="password">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_password_confirmation_{{ $doctor->id }}" class="form-label">Confirm Password</label>
                                                        <input type="password" class="form-control" 
                                                               id="edit_password_confirmation_{{ $doctor->id }}" name="password_confirmation">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Doctor</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Doctor Modal -->
                        <div class="modal fade" id="deleteDoctorModal{{ $doctor->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Are you sure you want to delete this doctor?</h5>
                                        <p class="mb-1"><strong>Dr. {{ $doctor->name }}</strong></p>
                                        <p class="text-muted">{{ $doctor->specialization }} • {{ $doctor->doctor_id ?? $doctor->id }}</p>
                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. All associated data will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Doctor
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
                    Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} entries
                </div>
                <div>
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Doctors Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'specialization', 'status', 'experience']))
                        No doctors match your search criteria.
                    @else
                        No doctors are currently registered in the system.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'specialization', 'status', 'experience']))
                <a href="{{ route('admin.doctors') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                    <i class="fas fa-plus me-1"></i>Add First Doctor
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Doctor Modal -->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addDoctorModalLabel">Add New Doctor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.doctors.store') }}" method="POST" id="addDoctorForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror phone-input" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       pattern="[0-9]{10}" maxlength="10" required>
                                <div class="form-text">Enter 10-digit phone number without spaces or dashes</div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qualification" class="form-label">Qualification *</label>
                                <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                                       id="qualification" name="qualification" value="{{ old('qualification') }}" required>
                                @error('qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specialization" class="form-label">Specialization *</label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">Experience (Years) *</label>
                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                       id="experience_years" name="experience_years" value="{{ old('experience_years', 0) }}" 
                                       min="0" max="50" required>
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_number" class="form-label">License Number</label>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                       id="license_number" name="license_number" value="{{ old('license_number') }}">
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active', 1) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !old('is_active', 1) ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Biography</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" name="bio" rows="3" placeholder="Optional biography about the doctor">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expertise" class="form-label">Areas of Expertise</label>
                        <textarea class="form-control @error('expertise') is-invalid @enderror" 
                                  id="expertise" name="expertise" rows="2" 
                                  placeholder="List areas of expertise separated by commas">{{ old('expertise') }}</textarea>
                        @error('expertise')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="available_from" class="form-label">Available From</label>
                                <input type="time" class="form-control @error('available_from') is-invalid @enderror" 
                                       id="available_from" name="available_from" value="{{ old('available_from') }}">
                                @error('available_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="available_to" class="form-label">Available To</label>
                                <input type="time" class="form-control @error('available_to') is-invalid @enderror" 
                                       id="available_to" name="available_to" value="{{ old('available_to') }}">
                                @error('available_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Doctor
                    </button>
                </div>
            </form>
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

    /* Phone input validation styles */
    .phone-input.valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .phone-input.invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
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
        const addDoctorModal = document.getElementById('addDoctorModal');
        if (addDoctorModal) {
            addDoctorModal.addEventListener('hidden.bs.modal', function () {
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
            const addModal = new bootstrap.Modal(document.getElementById('addDoctorModal'));
            addModal.show();
        @endif

        // Phone number validation and formatting
        const phoneInputs = document.querySelectorAll('.phone-input');
        phoneInputs.forEach(function(input) {
            // Format phone number display
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                // Limit to 10 digits
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                
                e.target.value = value;
                
                // Validate phone number
                validatePhoneNumber(e.target);
            });

            // Validate on blur
            input.addEventListener('blur', function(e) {
                validatePhoneNumber(e.target);
            });

            // Validate on form submission
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validatePhoneNumber(input)) {
                        e.preventDefault();
                        input.focus();
                    }
                });
            }
        });

        // Phone validation function
        function validatePhoneNumber(input) {
            const value = input.value.replace(/\D/g, '');
            const isValid = value.length === 10 && /^[6-9]\d{9}$/.test(value);
            
            // Update visual validation state
            if (value === '') {
                input.classList.remove('valid', 'invalid');
            } else if (isValid) {
                input.classList.remove('invalid');
                input.classList.add('valid');
            } else {
                input.classList.remove('valid');
                input.classList.add('invalid');
            }
            
            return isValid || value === '';
        }

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

        // Form validation for experience years
        function validateExperience(input) {
            const value = parseInt(input.value);
            if (value < 0) {
                input.value = 0;
            }
            if (value > 50) {
                input.value = 50;
            }
        }

        // Add experience validation to all experience inputs
        const experienceInputs = document.querySelectorAll('input[name="experience_years"]');
        experienceInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateExperience(this);
            });
        });
    });

    // Global function for experience validation
    function validateExperience(input) {
        const value = parseInt(input.value);
        if (value < 0) {
            input.value = 0;
        }
        if (value > 50) {
            input.value = 50;
        }
    }
</script>
@endsection