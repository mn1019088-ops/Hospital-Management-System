@extends('layouts.admin')

@section('title', 'Reception Staff Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reception Staff Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReceptionModal">
                <i class="fas fa-plus me-2"></i>Add New Reception Staff
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
            $totalReceptions = $receptions->total();
            $activeReceptions = $receptions->where('is_active', true)->count();
            $inactiveReceptions = $receptions->where('is_active', false)->count();
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reception Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReceptions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
                                Active Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeReceptions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Inactive Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inactiveReceptions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
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
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $receptions->where('created_at', '>=', now()->startOfMonth())->count() }}
                            </div>
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
                <form action="{{ route('admin.receptions') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by name, email, or phone..." 
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
                            <label for="date" class="form-label">Joined Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ request('date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.receptions') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reception Staff Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-tie me-2"></i>Reception Staff List
                <span class="badge bg-light text-primary ms-2">{{ $receptions->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $receptions->firstItem() ?? 0 }}-{{ $receptions->lastItem() ?? 0 }} of {{ $receptions->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($receptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Staff Member</th>
                            <th class="border-0">Contact Info</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Join Date</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receptions as $reception)
                        <tr>
                            <td>
                                <strong>#{{ $reception->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($reception->profile_image)
                                            <img src="{{ asset('storage/' . $reception->profile_image) }}" 
                                                 alt="{{ $reception->name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $reception->name }}</strong>
                                        <small class="text-muted">Reception Staff</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="d-block">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        {{ $reception->email }}
                                    </small>
                                    @if($reception->phone)
                                    <small class="d-block">
                                        <i class="fas fa-phone me-1 text-muted"></i>
                                        {{ $reception->phone }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $reception->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $reception->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong class="d-block">{{ $reception->created_at->format('M d, Y') }}</strong>
                                    <small class="text-muted">
                                        {{ $reception->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewReceptionModal{{ $reception->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editReceptionModal{{ $reception->id }}" 
                                            title="Edit Staff">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.receptions.toggle-status', $reception->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $reception->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                title="{{ $reception->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $reception->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReceptionModal{{ $reception->id }}" 
                                            title="Delete Staff">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Reception Modal -->
                        <div class="modal fade" id="viewReceptionModal{{ $reception->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user-tie me-2"></i>Reception Staff Details - {{ $reception->name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Personal Information</h6>
                                                <p><strong>Staff ID:</strong> #{{ $reception->id }}</p>
                                                <p><strong>Full Name:</strong> {{ $reception->name }}</p>
                                                <p><strong>Email:</strong> {{ $reception->email }}</p>
                                                <p><strong>Phone:</strong> {{ $reception->phone ?? 'Not provided' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Account Information</h6>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge {{ $reception->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $reception->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </p>
                                                <p><strong>Joined:</strong> {{ $reception->created_at->format('F d, Y') }}</p>
                                                <p><strong>Last Login:</strong> 
                                                    {{ $reception->last_login_at ? $reception->last_login_at->format('F d, Y h:i A') : 'Never logged in' }}
                                                </p>
                                                <p><strong>Last Updated:</strong> {{ $reception->updated_at->format('F d, Y') }}</p>
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

                        <!-- Edit Reception Modal -->
                        <div class="modal fade" id="editReceptionModal{{ $reception->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">Edit Reception Staff - {{ $reception->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.receptions.update', $reception->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_name_{{ $reception->id }}" class="form-label">Full Name *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="edit_name_{{ $reception->id }}" name="name" value="{{ old('name', $reception->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_email_{{ $reception->id }}" class="form-label">Email Address *</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="edit_email_{{ $reception->id }}" name="email" value="{{ old('email', $reception->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_phone_{{ $reception->id }}" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="edit_phone_{{ $reception->id }}" name="phone" value="{{ old('phone', $reception->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_is_active_{{ $reception->id }}" class="form-label">Status</label>
                                                <select class="form-select @error('is_active') is-invalid @enderror" 
                                                        id="edit_is_active_{{ $reception->id }}" name="is_active">
                                                    <option value="1" {{ old('is_active', $reception->is_active) ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !old('is_active', $reception->is_active) ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_password_{{ $reception->id }}" class="form-label">Password</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="edit_password_{{ $reception->id }}" name="password" placeholder="Leave blank to keep current password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Minimum 8 characters with letters and numbers</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Staff</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Reception Modal -->
                        <div class="modal fade" id="deleteReceptionModal{{ $reception->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Are you sure you want to delete this reception staff?</h5>
                                        <p class="mb-1"><strong>{{ $reception->name }}</strong></p>
                                        <p class="text-muted">#{{ $reception->id }} • {{ $reception->email }}</p>
                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. All staff data will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.receptions.destroy', $reception->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Staff
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
                    Showing {{ $receptions->firstItem() }} to {{ $receptions->lastItem() }} of {{ $receptions->total() }} entries
                </div>
                <div>
                    {{ $receptions->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Reception Staff Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'status', 'date']))
                        No reception staff match your search criteria.
                    @else
                        No reception staff are currently registered in the system.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'status', 'date']))
                <a href="{{ route('admin.receptions') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </a>
                @endif
                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addReceptionModal">
                    <i class="fas fa-plus me-1"></i>Add First Staff
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Reception Modal -->
<div class="modal fade" id="addReceptionModal" tabindex="-1" aria-labelledby="addReceptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addReceptionModalLabel">Add New Reception Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.receptions.store') }}" method="POST" id="addReceptionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimum 6 characters with letters and numbers</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Staff
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

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
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
        const addReceptionModal = document.getElementById('addReceptionModal');
        if (addReceptionModal) {
            addReceptionModal.addEventListener('hidden.bs.modal', function () {
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
            const addModal = new bootstrap.Modal(document.getElementById('addReceptionModal'));
            addModal.show();
        @endif

        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[name="phone"]');
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
                
                if (password.length >= 6) {
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
    });
</script>
@endsection