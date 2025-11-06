@extends('layouts.reception')

@section('title', 'Patient Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient Details</h1>
    <div>
        <a href="{{ route('reception.patients.list') }}" class="btn btn-primary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
        <a href="{{ route('reception.appointments') }}" class="btn btn-success">
            <i class="fas fa-calendar-plus me-2"></i>Book Appointment
        </a>
    </div>
</div>

<div class="row">
    <!-- Patient Information -->
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Patient Information</h5>
            </div>
            <div class="card-body text-center">
                <h4>{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                <p class="text-muted mb-1">Patient ID: <strong>{{ $patient->patient_id }}</strong></p>
                <p class="text-muted">Registered on: {{ $patient->created_at->format('M d, Y') }}</p>

                <div class="list-group list-group-flush text-start mt-3">
                    <div class="list-group-item"><strong>Email:</strong> {{ $patient->email ?? 'N/A' }}</div>
                    <div class="list-group-item"><strong>Phone:</strong> {{ $patient->phone ?? 'N/A' }}</div>
                    <div class="list-group-item"><strong>Date of Birth:</strong> {{ $patient->date_of_birth->format('d M, Y') }}</div>
                    <div class="list-group-item"><strong>Age:</strong> {{ $patient->age }} years</div>
                    <div class="list-group-item">
                        <strong>Gender:</strong>
                        <span class="badge bg-info">{{ ucfirst($patient->gender) }}</span>
                    </div>
                    <div class="list-group-item">
                        <strong>Blood Group:</strong>
                        @if($patient->blood_group)
                            <span class="badge bg-success">{{ $patient->blood_group }}</span>
                        @else
                            <span class="text-muted">Not Set</span>
                        @endif
                    </div>
                    <div class="list-group-item">
                        <strong>Status:</strong>
                        <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $patient->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                @if($patient->address)
                <div class="mt-3 text-start">
                    <strong>Address:</strong>
                    <p class="mb-0 small">{{ $patient->address }}</p>
                </div>
                @endif

                <div class="mt-4">
                    <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#editPatientModal">
                        <i class="fas fa-edit me-2"></i>Edit Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment History -->
    <div class="col-md-8">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Appointment History</h5>
            </div>
            <div class="card-body">
                @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Doctor</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td>Dr. {{ $appointment->doctor->name }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($appointment->appointment_type) }}</span></td>
                                <td>
                                    <span class="badge 
                                        {{ $appointment->status == 'completed' ? 'bg-success' : ($appointment->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($appointment->fee, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No Appointment History</h5>
                    <p class="text-muted">This patient has no appointment records yet.</p>
                    <a href="{{ route('reception.appointments') }}" class="btn btn-success">
                        <i class="fas fa-calendar-plus me-2"></i>Book First Appointment
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h3>{{ $appointments->count() }}</h3>
                        <p class="mb-0">Total Appointments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h3>{{ $appointments->where('status', 'completed')->count() }}</h3>
                        <p class="mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white text-center">
                    <div class="card-body">
                        <h3>{{ $appointments->whereIn('status', ['scheduled', 'confirmed'])->count() }}</h3>
                        <p class="mb-0">Upcoming</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Patient Modal -->
<div class="modal fade" id="editPatientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reception.patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="{{ $patient->first_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $patient->last_name }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $patient->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ $patient->phone }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ $patient->date_of_birth->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-control" name="gender" required>
                            <option value="male" {{ $patient->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $patient->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $patient->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Blood Group</label>
                        <select class="form-control" name="blood_group">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ $patient->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3">{{ $patient->address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
