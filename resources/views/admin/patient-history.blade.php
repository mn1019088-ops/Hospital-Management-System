@extends('layouts.admin')

@section('title', 'Medical History')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Medical History - {{ $patient->first_name }} {{ $patient->last_name }}</h1>
    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-secondary">Back to Patient</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Appointments</h5>
    </div>
    <div class="card-body">
        @if($appointments->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Reason</th>
                        <th>Prescription</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->reason ?? 'N/A' }}</td>
                        <td>{{ $appointment->prescription ?? 'N/A' }}</td>
                        <td>{{ $appointment->notes ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted">
            <i class="fas fa-notes-medical fa-2x mb-2"></i><br>No medical history found
        </div>
        @endif
    </div>
</div>
@endsection
