@extends('layouts.admin')

@section('title', 'Department Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Department Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="fas fa-plus me-2"></i>Add New Department
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
            $totalDepartments = $departments->total();
            $activeDepartments = $departments->where('is_active', true)->count();
            $departmentsWithHeads = $departments->whereNotNull('head_doctor_id')->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Departments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDepartments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                Active Departments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeDepartments }}</div>
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
                                With Head Doctors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departmentsWithHeads }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
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
                                Inactive Departments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDepartments - $activeDepartments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                <form action="{{ route('admin.departments') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by department name..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="floor" class="form-label">Floor</label>
                            <select class="form-select" id="floor" name="floor">
                                <option value="">All Floors</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('floor') == $i ? 'selected' : '' }}>Floor {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Departments Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-building me-2"></i>Department List
                <span class="badge bg-light text-primary ms-2">{{ $departments->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $departments->firstItem() ?? 0 }}-{{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($departments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Department</th>
                            <th class="border-0">Head Doctor</th>
                            <th class="border-0">Location</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                        <tr>
                            <td>
                                <strong>#{{ $department->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block">{{ $department->name }}</strong>
                                    @if($department->description)
                                    <small class="text-muted" title="{{ $department->description }}">
                                        {{ Str::limit($department->description, 50) }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($department->headDoctor)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        @if($department->headDoctor->profile_image)
                                            <img src="{{ asset('storage/' . $department->headDoctor->profile_image) }}" 
                                                 alt="Dr. {{ $department->headDoctor->name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">Dr. {{ $department->headDoctor->name }}</strong>
                                        <small class="text-muted">{{ $department->headDoctor->specialization }}</small>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($department->floor)
                                <div class="text-nowrap">
                                    <strong>{{ $department->floor }} Floor</strong>
                                </div>
                                @else
                                <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($department->contact_email)
                                    <small class="d-block">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        {{ $department->contact_email }}
                                    </small>
                                    @endif
                                    @if($department->contact_phone)
                                    <small class="d-block">
                                        <i class="fas fa-phone me-1 text-muted"></i>
                                        {{ $department->contact_phone }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewDepartmentModal{{ $department->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}" 
                                            title="Edit Department">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.departments.toggle-status', $department->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $department->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                title="{{ $department->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $department->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal{{ $department->id }}" 
                                            title="Delete Department">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Department Modal -->
                        <div class="modal fade" id="viewDepartmentModal{{ $department->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-building me-2"></i>Department Details - {{ $department->name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Department Information</h6>
                                                <p><strong>Department ID:</strong> #{{ $department->id }}</p>
                                                <p><strong>Name:</strong> {{ $department->name }}</p>
                                                <p><strong>Description:</strong> {{ $department->description ?? 'Not provided' }}</p>
                                                <p><strong>Floor:</strong> {{ $department->floor ? 'Floor ' . $department->floor : 'Not specified' }}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Contact Information</h6>
                                                <p><strong>Email:</strong> {{ $department->contact_email ?? 'Not provided' }}</p>
                                                <p><strong>Phone:</strong> {{ $department->contact_phone ?? 'Not provided' }}</p>
                                                
                                                <h6 class="border-bottom pb-2 mt-3">Head Doctor</h6>
                                                @if($department->headDoctor)
                                                <p><strong>Name:</strong> Dr. {{ $department->headDoctor->name }}</p>
                                                <p><strong>Specialization:</strong> {{ $department->headDoctor->specialization }}</p>
                                                <p><strong>Email:</strong> {{ $department->headDoctor->email }}</p>
                                                <p><strong>Phone:</strong> {{ $department->headDoctor->phone ?? 'N/A' }}</p>
                                                @else
                                                <p class="text-muted">No head doctor assigned</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Additional Information</h6>
                                                <p><strong>Created:</strong> {{ $department->created_at->format('F d, Y') }}</p>
                                                <p><strong>Last Updated:</strong> {{ $department->updated_at->format('F d, Y') }}</p>
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

                        <!-- Edit Department Modal -->
                        <div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">Edit Department - {{ $department->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_name_{{ $department->id }}" class="form-label">Department Name *</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                               id="edit_name_{{ $department->id }}" name="name" value="{{ old('name', $department->name) }}" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_floor_{{ $department->id }}" class="form-label">Floor</label>
                                                        <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                                               id="edit_floor_{{ $department->id }}" name="floor" value="{{ old('floor', $department->floor) }}" 
                                                               min="1" max="20">
                                                        @error('floor')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit_description_{{ $department->id }}" class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                                          id="edit_description_{{ $department->id }}" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_head_doctor_id_{{ $department->id }}" class="form-label">Head Doctor</label>
                                                        <select class="form-select @error('head_doctor_id') is-invalid @enderror" 
                                                                id="edit_head_doctor_id_{{ $department->id }}" name="head_doctor_id">
                                                            <option value="">Select Head Doctor</option>
                                                            @foreach($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ old('head_doctor_id', $department->head_doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                                    Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('head_doctor_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_is_active_{{ $department->id }}" class="form-label">Status</label>
                                                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                                                id="edit_is_active_{{ $department->id }}" name="is_active">
                                                            <option value="1" {{ old('is_active', $department->is_active) ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !old('is_active', $department->is_active) ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                        @error('is_active')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_contact_email_{{ $department->id }}" class="form-label">Contact Email</label>
                                                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                                               id="edit_contact_email_{{ $department->id }}" name="contact_email" value="{{ old('contact_email', $department->contact_email) }}">
                                                        @error('contact_email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_contact_phone_{{ $department->id }}" class="form-label">Contact Phone</label>
                                                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                                               id="edit_contact_phone_{{ $department->id }}" name="contact_phone" value="{{ old('contact_phone', $department->contact_phone) }}">
                                                        @error('contact_phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Department</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Department Modal -->
                        <div class="modal fade" id="deleteDepartmentModal{{ $department->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Are you sure you want to delete this department?</h5>
                                        <p class="mb-1"><strong>{{ $department->name }}</strong></p>
                                        <p class="text-muted">#{{ $department->id }} • {{ $department->headDoctor ? 'Dr. ' . $department->headDoctor->name : 'No Head Doctor' }}</p>
                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. All department data will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Department
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
                    Showing {{ $departments->firstItem() }} to {{ $departments->lastItem() }} of {{ $departments->total() }} entries
                </div>
                <div>
                    {{ $departments->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Departments Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'status', 'floor']))
                        No departments match your search criteria.
                    @else
                        No departments are currently registered in the system.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'status', 'floor']))
                <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                    <i class="fas fa-plus me-1"></i>Add First Department
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.departments.store') }}" method="POST" id="addDepartmentForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Department Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="floor" class="form-label">Floor</label>
                                <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                       id="floor" name="floor" value="{{ old('floor') }}" min="1" max="20">
                                @error('floor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="head_doctor_id" class="form-label">Head Doctor</label>
                                <select class="form-select @error('head_doctor_id') is-invalid @enderror" id="head_doctor_id" name="head_doctor_id">
                                    <option value="">Select Head Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('head_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('head_doctor_id')
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Department
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
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
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
        const addDepartmentModal = document.getElementById('addDepartmentModal');
        if (addDepartmentModal) {
            addDepartmentModal.addEventListener('hidden.bs.modal', function () {
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
            const addModal = new bootstrap.Modal(document.getElementById('addDepartmentModal'));
            addModal.show();
        @endif

        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[name="contact_phone"]');
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

        // Floor number validation
        const floorInputs = document.querySelectorAll('input[name="floor"]');
        floorInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const value = parseInt(e.target.value);
                if (value < 1) {
                    e.target.value = 1;
                }
                if (value > 20) {
                    e.target.value = 20;
                }
            });
        });
    });
</script>
@endsection