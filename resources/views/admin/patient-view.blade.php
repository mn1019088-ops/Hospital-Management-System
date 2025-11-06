@extends('layouts.admin')

@section('title', 'Show Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient Details</h1>
    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
        <span class="badge {{ $patient->is_active ? 'bg-success' : 'bg-danger' }}">
            {{ $patient->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Patient ID:</strong> {{ $patient->patient_id }}
            </div>
            <div class="col-md-6">
                <strong>Email:</strong> {{ $patient->email ?? 'N/A' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"><strong>Age:</strong> {{ $patient->age ?? 'N/A' }} years</div>
            <div class="col-md-4"><strong>Gender:</strong> {{ ucfirst($patient->gender ?? 'N/A') }}</div>
            <div class="col-md-4"><strong>Phone:</strong> {{ $patient->phone ?? 'N/A' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"><strong>Blood Group:</strong> {{ $patient->blood_group ?? 'Not set' }}</div>
            <div class="col-md-4"><strong>Address:</strong> {{ $patient->address ?? 'Not provided' }}</div>
            <div class="col-md-4"><strong>Registered:</strong> {{ $patient->created_at->format('M d, Y') }}</div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.patients.history', $patient->id) }}" class="btn btn-primary">
                <i class="fas fa-file-medical"></i> View Medical History
            </a>
            <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Details
            </a>
        </div>
    </div>
</div>
@endsection
