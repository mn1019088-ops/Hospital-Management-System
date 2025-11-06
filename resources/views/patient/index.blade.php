@extends('layouts.app')

@section('title', 'Patients Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Patients Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add New Patient
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('patients.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="gender" class="form-select">
                            <option value="">All Gender</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td>
                                <strong>{{ $patient->patient_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder bg-primary rounded-circle me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        <small class="text-muted">ID: {{ $patient->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $patient->email ?? 'N/A' }}</td>
                            <td>{{ $patient->phone ?? 'N/A' }}</td>
                            <td>
                                @if($patient->date_of_birth)
                                    {{ $patient->age }} years
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-capitalize">{{ $patient->gender }}</span>
                            </td>
                            <td>
                                @if($patient->blood_group)
                                    <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }}">
                                    {{ $patient->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $patient->is_active ? 'warning' : 'success' }}" 
                                            onclick="toggleStatus({{ $patient->id }})">
                                        <i class="fas fa-{{ $patient->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this patient?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No patients found</h5>
                                <a href="{{ route('patients.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-1"></i>Add First Patient
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} entries
                </div>
                {{ $patients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<form id="toggle-status-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<script>
function toggleStatus(patientId) {
    if (confirm('Are you sure you want to change the patient status?')) {
        const form = document.getElementById('toggle-status-form');
        form.action = `/patients/${patientId}/toggle-status`;
        form.submit();
    }
}
</script>

<style>
.avatar-placeholder {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection