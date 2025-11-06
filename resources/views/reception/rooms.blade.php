@extends('layouts.reception')

@section('title', 'Room Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Room Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('reception.rooms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add New Room
            </a>
        </div>
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

    <!-- Room Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Rooms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_rooms'] }}</div>
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
                                Available Rooms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available_rooms'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
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
                                Occupied Rooms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['occupied_rooms'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-procedures fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Maintenance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['maintenance_rooms'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                <form action="{{ route('reception.rooms') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Room number or type..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="room_type" class="form-label">Room Type</label>
                            <select class="form-select" id="room_type" name="room_type">
                                <option value="">All Types</option>
                                <option value="general" {{ request('room_type') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="private" {{ request('room_type') == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="icu" {{ request('room_type') == 'icu' ? 'selected' : '' }}>ICU</option>
                                <option value="emergency" {{ request('room_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="operation-theater" {{ request('room_type') == 'operation-theater' ? 'selected' : '' }}>Operation Theater</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="number" class="form-control" id="floor" name="floor" 
                                   placeholder="Floor number..." 
                                   value="{{ request('floor') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Room Table Card -->
    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-bed me-2"></i>Room List
                <span class="badge bg-light text-primary ms-2">{{ $rooms->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $rooms->firstItem() ?? 0 }}-{{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($rooms->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Room Number</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Department</th>
                            <th class="border-0">Floor</th>
                            <th class="border-0">Capacity</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Price/Day</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
                            <td>
                                <strong class="d-block">{{ $room->room_number }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info text-capitalize">
                                    {{ str_replace('-', ' ', $room->room_type) }}
                                </span>
                            </td>
                            <td>
                                {{ $room->department->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">Floor {{ $room->floor }}</span>
                            </td>
                            <td>
                                <strong>{{ $room->capacity }}</strong> patients
                            </td>
                            <td>
                                <span class="badge 
                                    @if($room->status == 'available') bg-success
                                    @elseif($room->status == 'occupied') bg-warning text-dark
                                    @elseif($room->status == 'maintenance') bg-danger
                                    @else bg-info @endif">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td>
                                <strong>₹{{ number_format($room->price_per_day, 2) }}</strong>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}" 
                                            title="View Details" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <a href="{{ route('reception.rooms.edit', $room->id) }}" class="btn btn-warning" 
                                       title="Edit Room" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('reception.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                title="Delete Room" data-bs-toggle="tooltip"
                                                onclick="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- View Room Modal -->
                        <div class="modal fade" id="viewRoomModal{{ $room->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-info-circle me-2"></i>Room Details - {{ $room->room_number }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Basic Information</h6>
                                                <p><strong>Room Number:</strong> {{ $room->room_number }}</p>
                                                <p><strong>Type:</strong> 
                                                    <span class="badge bg-info text-capitalize">
                                                        {{ str_replace('-', ' ', $room->room_type) }}
                                                    </span>
                                                </p>
                                                <p><strong>Department:</strong> {{ $room->department->name ?? 'N/A' }}</p>
                                                <p><strong>Floor:</strong> {{ $room->floor }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">Room Details</h6>
                                                <p><strong>Capacity:</strong> {{ $room->capacity }} patients</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge 
                                                        @if($room->status == 'available') bg-success
                                                        @elseif($room->status == 'occupied') bg-warning text-dark
                                                        @elseif($room->status == 'maintenance') bg-danger
                                                        @else bg-info @endif">
                                                        {{ ucfirst($room->status) }}
                                                    </span>
                                                </p>
                                                <p><strong>Price per Day:</strong> ₹{{ number_format($room->price_per_day, 2) }}</p>
                                                <p><strong>Created:</strong> {{ $room->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($room->facilities)
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Facilities & Amenities</h6>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $room->facilities }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($room->notes)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2">Additional Notes</h6>
                                                <div class="bg-light p-3 rounded" style="white-space: pre-line;">{{ $room->notes }}</div>
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of {{ $rooms->total() }} entries
                </div>
                <div>
                    {{ $rooms->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Rooms Found</h4>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'room_type', 'status', 'floor']))
                        No rooms match your search criteria.
                    @else
                        No rooms have been added yet.
                    @endif
                </p>
                
                <a href="{{ route('reception.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Room
                </a>
                @if(request()->anyFilled(['search', 'room_type', 'status', 'floor']))
                <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">
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
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
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
    });
</script>
@endsection