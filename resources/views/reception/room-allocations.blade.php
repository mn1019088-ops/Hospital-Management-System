@extends('layouts.reception')

@section('title', 'Room Allocations')
@section('page-title', 'Room Allocations Management')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Room Allocations</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Room Allocations Management</h1>
        <a href="{{ route('reception.room-allocations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Room Allocation
        </a>
    </div>

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
            $totalAllocations = $allocations->total();
            $activeAllocations = $allocations->where('status', 'active')->count();
            $dischargedAllocations = $allocations->where('status', 'discharged')->count();
            $totalRevenue = $allocations->sum('paid_amount');
            $totalDue = $allocations->sum(function($allocation) {
                return max(0, $allocation->total_amount - $allocation->paid_amount);
            });
        @endphp

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Allocations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAllocations }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
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
                                Active Allocations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAllocations }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-procedures fa-2x text-gray-300"></i>
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
                                Revenue Collected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Pending Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalDue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                <!-- FIXED: Changed route from index to the actual route name -->
                <form action="{{ route('reception.room-allocations') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search Patient</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Patient name or ID..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="room_number" class="form-label">Room Number</label>
                            <input type="text" class="form-control" id="room_number" name="room_number" 
                                   placeholder="Room number..." 
                                   value="{{ request('room_number') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>Discharged</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ request('date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <!-- FIXED: Changed route from index to the actual route name -->
                            <a href="{{ route('reception.room-allocations') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Room Allocations Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-bed me-2"></i>Room Allocations List
                <span class="badge bg-light text-primary ms-2">{{ $allocations->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $allocations->firstItem() ?? 0 }}-{{ $allocations->lastItem() ?? 0 }} of {{ $allocations->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($allocations->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Room</th>
                            <th class="border-0">Admission Date</th>
                            <th class="border-0">Days Stayed</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Total Amount</th>
                            <th class="border-0">Paid Amount</th>
                            <th class="border-0">Due Amount</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allocations as $allocation)
                        @php
                            // Calculate days stayed
                            $today = \Carbon\Carbon::today();
                            $admissionDate = \Carbon\Carbon::parse($allocation->admission_date);
                            
                            if ($allocation->status === 'active') {
                                $daysStayed = $admissionDate->diffInDays($today) + 1;
                            } else {
                                $daysStayed = $allocation->actual_stay_days ?? $admissionDate->diffInDays(\Carbon\Carbon::parse($allocation->discharge_date)) + 1;
                            }
                            
                            // Use fixed total amount instead of day-by-day calculation
                            $totalAmount = $allocation->total_amount;
                            $dueAmount = max(0, $totalAmount - $allocation->paid_amount);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($allocations->currentPage() - 1) * $allocations->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($allocation->patient->profile_image)
                                            <img src="{{ asset('storage/' . $allocation->patient->profile_image) }}" 
                                                 alt="{{ $allocation->patient->first_name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}</strong>
                                        <small class="text-muted">ID: {{ $allocation->patient->patient_id ?? 'PAT' . $allocation->patient->id }}</small><br>
                                        <small class="text-muted">{{ $allocation->patient->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block">{{ $allocation->room->room_number }}</strong>
                                    <small class="text-muted text-capitalize">{{ $allocation->room->room_type }}</small><br>
                                    <small class="text-info">₹{{ number_format($allocation->room->price_per_day, 2) }}/day</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong>{{ $allocation->admission_date->format('M d, Y') }}</strong>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $daysStayed }}</strong> days
                            </td>
                            <td>
                                <span class="badge {{ $allocation->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                                @if($allocation->status === 'discharged')
                                    <br>
                                    <small class="text-muted">{{ $allocation->discharge_date->format('M d, Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>₹{{ number_format($totalAmount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="text-success fw-bold">₹{{ number_format($allocation->paid_amount, 2) }}</span>
                            </td>
                            <td>
                                @if($dueAmount > 0)
                                    <span class="text-danger fw-bold">
                                        ₹{{ number_format($dueAmount, 2) }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewAllocationModal{{ $allocation->id }}" 
                                            title="View Details" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($allocation->status === 'active')
                                        <!-- Edit Button -->
                                        <a href="{{ route('reception.room-allocations.edit', $allocation->id) }}" class="btn btn-warning" 
                                           title="Edit Allocation" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Payment Button -->
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $allocation->id }}" 
                                                title="Add Payment" data-bs-toggle="tooltip">
                                            <i class="fas fa-money-bill"></i>
                                        </button>

                                        <!-- Discharge Button -->
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dischargeModal{{ $allocation->id }}" 
                                                title="Discharge Patient" data-bs-toggle="tooltip">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- View Allocation Modal -->
                        <div class="modal fade" id="viewAllocationModal{{ $allocation->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-info-circle me-2"></i>Allocation Details - #{{ $allocation->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Patient Information</h6>
                                                <p><strong>Name:</strong> {{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}</p>
                                                <p><strong>Patient ID:</strong> {{ $allocation->patient->patient_id ?? 'PAT' . $allocation->patient->id }}</p>
                                                <p><strong>Phone:</strong> {{ $allocation->patient->phone }}</p>
                                                <p><strong>Email:</strong> {{ $allocation->patient->email ?? 'N/A' }}</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($allocation->patient->gender) }}</p>
                                                <p><strong>Age:</strong> {{ $allocation->patient->date_of_birth ? \Carbon\Carbon::parse($allocation->patient->date_of_birth)->age . ' years' : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Room Information</h6>
                                                <p><strong>Room Number:</strong> {{ $allocation->room->room_number }}</p>
                                                <p><strong>Room Type:</strong> {{ ucfirst($allocation->room->room_type) }}</p>
                                                <p><strong>Price per Day:</strong> ₹{{ number_format($allocation->room->price_per_day, 2) }}</p>
                                                <p><strong>Floor:</strong> {{ $allocation->room->floor ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Stay Details</h6>
                                                <p><strong>Admission Date:</strong> {{ $allocation->admission_date->format('M d, Y') }}</p>
                                                <p><strong>Estimated Stay:</strong> {{ $allocation->estimated_stay_days }} days</p>
                                                <p><strong>Days Stayed:</strong> {{ $daysStayed }} days</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge {{ $allocation->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ ucfirst($allocation->status) }}
                                                    </span>
                                                </p>
                                                @if($allocation->discharge_date)
                                                    <p><strong>Discharge Date:</strong> {{ $allocation->discharge_date->format('M d, Y') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Financial Information</h6>
                                                <p><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
                                                <p><strong>Paid Amount:</strong> ₹{{ number_format($allocation->paid_amount, 2) }}</p>
                                                <p><strong>Due Amount:</strong> ₹{{ number_format($dueAmount, 2) }}</p>
                                                @if($dueAmount > 0)
                                                    <div class="alert alert-warning py-2 mt-2">
                                                        <small>
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            <strong>Outstanding Balance:</strong> ₹{{ number_format($dueAmount, 2) }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <div class="alert alert-success py-2 mt-2">
                                                        <small>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            <strong>Fully Paid</strong>
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Medical Information</h6>
                                                <p><strong>Reason for Admission:</strong></p>
                                                <div class="bg-light p-3 rounded mb-3">
                                                    {{ $allocation->reason }}
                                                </div>
                                            </div>
                                        </div>

                                        @if($allocation->notes)
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6 class="border-bottom pb-2">Additional Notes</h6>
                                                    <div class="bg-light p-3 rounded" style="white-space: pre-line;">{{ $allocation->notes }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discharge Modal -->
                        @if($allocation->status === 'active')
                        <div class="modal fade" id="dischargeModal{{ $allocation->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- FIXED: Using URL helper instead of route for discharge -->
                                    <form method="POST" action="{{ url('/reception/room-allocations/' . $allocation->id . '/discharge') }}" id="dischargeForm{{ $allocation->id }}">
                                        @csrf
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-sign-out-alt me-2"></i>Discharge Patient
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <strong>Patient:</strong><br>
                                                        {{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Room:</strong><br>
                                                        {{ $allocation->room->room_number }}
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <strong>Admission Date:</strong> {{ $allocation->admission_date->format('M d, Y') }}
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label required">Discharge Date</label>
                                                <input type="date" name="discharge_date" 
                                                       class="form-control @error('discharge_date') is-invalid @enderror" 
                                                       value="{{ old('discharge_date', date('Y-m-d')) }}" 
                                                       min="{{ $allocation->admission_date->format('Y-m-d') }}" 
                                                       max="{{ date('Y-m-d') }}" required>
                                                @error('discharge_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Discharge Notes</label>
                                                <textarea name="discharge_notes" class="form-control" rows="3" 
                                                          placeholder="Optional discharge notes (condition, follow-up, etc.)...">{{ old('discharge_notes') }}</textarea>
                                            </div>

                                            <!-- Fixed Bill Summary -->
                                            <div class="alert alert-info">
                                                <h6 class="alert-heading">Bill Summary</h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small>Days Stayed:</small><br>
                                                        <strong>{{ $daysStayed }} days</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>Total Amount:</small><br>
                                                        <strong class="text-primary">₹{{ number_format($totalAmount, 2) }}</strong>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <small>Amount Paid:</small><br>
                                                        <strong class="text-success">₹{{ number_format($allocation->paid_amount, 2) }}</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>Due Amount:</small><br>
                                                        <strong class="text-danger">₹{{ number_format($dueAmount, 2) }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($dueAmount > 0)
                                                <div class="alert alert-warning">
                                                    <small>
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        <strong>Note:</strong> Patient has an outstanding balance of ₹{{ number_format($dueAmount, 2) }}. 
                                                        Consider collecting payment before discharge.
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-check me-2"></i>Confirm Discharge
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Modal -->
                        <div class="modal fade" id="paymentModal{{ $allocation->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- FIXED: Using URL helper instead of route for payment -->
                                    <form method="POST" action="{{ url('/reception/room-allocations/' . $allocation->id . '/payment') }}" id="paymentForm{{ $allocation->id }}">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-money-bill-wave me-2"></i>Add Payment
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Patient Information -->
                                            <div class="alert alert-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong><i class="fas fa-user me-2"></i>Patient:</strong><br>
                                                        {{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}<br>
                                                        <small class="text-muted">ID: {{ $allocation->patient->patient_id ?? 'PAT' . $allocation->patient->id }}</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong><i class="fas fa-bed me-2"></i>Room:</strong><br>
                                                        {{ $allocation->room->room_number }} ({{ $allocation->room->room_type }})<br>
                                                        <small class="text-muted">₹{{ number_format($allocation->room->price_per_day, 2) }}/day</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Current Balance Summary -->
                                                    <div class="card border-warning mb-3">
                                                        <div class="card-header bg-warning text-dark">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-calculator me-2"></i>Current Balance
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-2">
                                                                <small class="text-muted">Total Amount:</small>
                                                                <div class="fw-bold text-primary">₹{{ number_format($totalAmount, 2) }}</div>
                                                            </div>
                                                            
                                                            <div class="mb-2">
                                                                <small class="text-muted">Paid Amount:</small>
                                                                <div class="fw-bold text-success">₹{{ number_format($allocation->paid_amount, 2) }}</div>
                                                            </div>
                                                            
                                                            <div class="mb-2">
                                                                <small class="text-muted">Due Amount:</small>
                                                                <div class="fw-bold text-danger">₹{{ number_format($dueAmount, 2) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <!-- Payment Details -->
                                                    <div class="mb-3">
                                                        <label class="form-label required">Payment Amount (₹)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">₹</span>
                                                            <input type="number" name="payment_amount" 
                                                                   class="form-control @error('payment_amount') is-invalid @enderror" 
                                                                   id="payment_amount_{{ $allocation->id }}"
                                                                   step="0.01" min="0.01" max="{{ $dueAmount }}" 
                                                                   value="{{ old('payment_amount', $dueAmount) }}" 
                                                                   required oninput="updatePaymentSummary({{ $allocation->id }}, {{ $dueAmount }})">
                                                        </div>
                                                        @error('payment_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted">Maximum: ₹{{ number_format($dueAmount, 2) }}</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required">Payment Date</label>
                                                        <input type="date" name="payment_date" 
                                                               class="form-control @error('payment_date') is-invalid @enderror" 
                                                               value="{{ old('payment_date', date('Y-m-d')) }}" 
                                                               max="{{ date('Y-m-d') }}" required>
                                                        @error('payment_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Method</label>
                                                        <select name="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                                            <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                                        </select>
                                                        @error('payment_method')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Transaction Reference</label>
                                                        <input type="text" name="transaction_reference" 
                                                               class="form-control @error('transaction_reference') is-invalid @enderror" 
                                                               value="{{ old('transaction_reference') }}" 
                                                               placeholder="Optional transaction ID/reference">
                                                        @error('transaction_reference')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Notes</label>
                                                        <textarea name="payment_notes" class="form-control" rows="2" 
                                                                  placeholder="Optional payment notes...">{{ old('payment_notes') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Summary -->
                                            <div class="card border-success">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="card-title mb-0">
                                                        <i class="fas fa-receipt me-2"></i>Payment Summary
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Current Due:</small>
                                                            <div class="fw-bold text-danger" id="current_due_{{ $allocation->id }}">
                                                                ₹{{ number_format($dueAmount, 2) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Payment Amount:</small>
                                                            <div class="fw-bold text-success" id="payment_summary_{{ $allocation->id }}">
                                                                ₹{{ number_format($dueAmount, 2) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Remaining Due:</small>
                                                            <div class="fw-bold text-warning" id="remaining_due_{{ $allocation->id }}">
                                                                ₹0.00
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Process Payment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $allocations->firstItem() }} to {{ $allocations->lastItem() }} of {{ $allocations->total() }} entries
                </div>
                <div>
                    {{ $allocations->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Room Allocations Found</h4>
                <p class="text-muted">No room allocations match your search criteria.</p>
                <a href="{{ route('reception.room-allocations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Create New Allocation
                </a>
                <!-- FIXED: Changed route from index to the actual route name -->
                <a href="{{ route('reception.room-allocations') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
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

        // Set max date for payment and discharge dates
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[name="discharge_date"], input[name="payment_date"]').forEach(input => {
            input.setAttribute('max', today);
        });

        // Initialize payment summaries
        document.querySelectorAll('[id^="payment_amount_"]').forEach(input => {
            const allocationId = input.id.split('_')[2];
            const maxAmount = parseFloat(input.getAttribute('max')) || 0;
            updatePaymentSummary(allocationId, maxAmount);
        });

        // Filter collapse persistence
        const filterCollapse = document.getElementById('filterCollapse');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.toString()) {
            new bootstrap.Collapse(filterCollapse, { show: true });
        }

        // Form validation for payment forms
        document.querySelectorAll('form[id^="paymentForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const paymentInput = this.querySelector('input[name="payment_amount"]');
                const maxAmount = parseFloat(paymentInput.getAttribute('max'));
                const paymentAmount = parseFloat(paymentInput.value) || 0;
                
                if (paymentAmount > maxAmount) {
                    e.preventDefault();
                    alert(`Payment amount cannot exceed ₹${maxAmount.toFixed(2)}`);
                    paymentInput.focus();
                    return false;
                }
                
                if (paymentAmount <= 0) {
                    e.preventDefault();
                    alert('Please enter a valid payment amount');
                    paymentInput.focus();
                    return false;
                }
            });
        });

        // Form validation for discharge forms
        document.querySelectorAll('form[id^="dischargeForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const dischargeDateInput = this.querySelector('input[name="discharge_date"]');
                const dischargeDate = new Date(dischargeDateInput.value);
                const today = new Date();
                
                if (dischargeDate > today) {
                    e.preventDefault();
                    alert('Discharge date cannot be in the future');
                    dischargeDateInput.focus();
                    return false;
                }
            });
        });
    });

    function updatePaymentSummary(allocationId, maxAmount) {
        const paymentInput = document.getElementById('payment_amount_' + allocationId);
        const paymentAmount = parseFloat(paymentInput.value) || 0;
        
        // Update payment summary display
        document.getElementById('payment_summary_' + allocationId).textContent = '₹' + paymentAmount.toFixed(2);
        
        // Calculate and update remaining due
        const currentDue = maxAmount;
        const remainingDue = Math.max(0, currentDue - paymentAmount);
        const remainingDueElement = document.getElementById('remaining_due_' + allocationId);
        remainingDueElement.textContent = '₹' + remainingDue.toFixed(2);
        
        // Validate payment amount and update colors
        if (paymentAmount > maxAmount) {
            paymentInput.setCustomValidity(`Payment amount cannot exceed ₹${maxAmount.toFixed(2)}`);
            remainingDueElement.classList.remove('text-warning', 'text-success');
            remainingDueElement.classList.add('text-danger');
        } else {
            paymentInput.setCustomValidity('');
            if (remainingDue === 0) {
                remainingDueElement.classList.remove('text-warning', 'text-danger');
                remainingDueElement.classList.add('text-success');
            } else {
                remainingDueElement.classList.remove('text-success', 'text-danger');
                remainingDueElement.classList.add('text-warning');
            }
        }
    }

    // Auto-format payment input
    document.addEventListener('input', function(e) {
        if (e.target.name === 'payment_amount') {
            const allocationId = e.target.id.split('_')[2];
            const maxAmount = parseFloat(e.target.getAttribute('max')) || 0;
            updatePaymentSummary(allocationId, maxAmount);
        }
    });
</script>
@endsection