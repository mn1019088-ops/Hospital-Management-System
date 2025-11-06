@extends('layouts.doctor')

@section('title', 'Medical Records')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 mb-0">
        <i class="fas fa-file-medical me-2"></i>Medical Records
    </h1>
    <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary mt-2 mt-md-0">
        <i class="fas fa-plus me-2"></i> Add New Record
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $doctorId = Auth::guard('doctor')->id();
        $stats = [
            'total_records' => $medicalRecords->total(),
            'today_records' => \App\Models\MedicalRecord::where('doctor_id', $doctorId)
                ->whereDate('created_at', today())
                ->count(),
            'this_month' => \App\Models\MedicalRecord::where('doctor_id', $doctorId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'unique_patients' => \App\Models\MedicalRecord::where('doctor_id', $doctorId)
                ->distinct('patient_id')
                ->count('patient_id')
        ];
    @endphp

    @foreach([
        'total_records' => ['Total Records', 'primary', 'fas fa-file-medical'],
        'today_records' => ['Today\'s Records', 'success', 'fas fa-calendar-day'],
        'this_month' => ['This Month', 'info', 'fas fa-calendar-alt'],
        'unique_patients' => ['Unique Patients', 'warning', 'fas fa-users']
    ] as $key => [$title, $color, $icon])
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-{{ $color }} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                            {{ $title }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats[$key] }}</div>
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
            <form action="{{ route('doctor.medical-records') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by patient, diagnosis, or treatment..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Medical Records Table Card -->
<div class="card border-light shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>All Medical Records
            <span class="badge bg-light text-primary ms-2">{{ $medicalRecords->total() }}</span>
        </h5>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">Showing {{ $medicalRecords->firstItem() ?? 0 }}-{{ $medicalRecords->lastItem() ?? 0 }} of {{ $medicalRecords->total() }}</span>
        </div>
    </div>

    <div class="card-body">
        @if($medicalRecords->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Record ID</th>
                        <th>Patient Information</th>
                        <th>Visit Date</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Vital Signs</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalRecords as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($medicalRecords->currentPage() - 1) * $medicalRecords->perPage() }}</td>
                            <td>
                                <strong>{{ $record->record_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($record->patient->profile_image)
                                            <img src="{{ asset('storage/' . $record->patient->profile_image) }}" 
                                                 alt="{{ $record->patient->first_name }}" 
                                                 class="rounded-circle avatar-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $record->patient->first_name }} {{ $record->patient->last_name }}</strong>
                                        <small class="text-muted">
                                            ID: {{ $record->patient->patient_id }}<br>
                                            Age: {{ $record->patient->date_of_birth ? \Carbon\Carbon::parse($record->patient->date_of_birth)->age . 'y' : 'N/A' }} | 
                                            {{ ucfirst($record->patient->gender) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong>{{ $record->visit_date->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ $record->visit_date->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" title="{{ $record->diagnosis }}">
                                    {{ \Illuminate\Support\Str::limit($record->diagnosis, 40) }}
                                </span>
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" title="{{ $record->treatment }}">
                                    {{ \Illuminate\Support\Str::limit($record->treatment, 40) }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    @if($record->blood_pressure)
                                        <span class="badge bg-info me-1" title="Blood Pressure">{{ $record->blood_pressure }}</span>
                                    @endif
                                    @if($record->weight)
                                        <span class="badge bg-secondary me-1" title="Weight">{{ $record->weight }}kg</span>
                                    @endif
                                    @if($record->bmi)
                                        <span class="badge bg-warning" title="BMI">{{ $record->bmi }}</span>
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('doctor.medical-records.show', $record->id) }}" 
                                       class="btn btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('doctor.medical-records.edit', $record->id) }}" 
                                       class="btn btn-warning" title="Edit Record">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('doctor.medical-records.destroy', $record->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                title="Delete Record"
                                                onclick="return confirm('Are you sure you want to delete this medical record? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                Showing {{ $medicalRecords->firstItem() }} to {{ $medicalRecords->lastItem() }} of {{ $medicalRecords->total() }} entries
            </div>
            <div>
                {{ $medicalRecords->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No Medical Records Found</h4>
            <p class="text-muted">No medical records match your search criteria.</p>
            <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-1"></i>Create First Record
            </a>
            <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary">
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

        // Date validation - ensure date_to is not before date_from
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        
        if (dateFrom && dateTo) {
            dateFrom.addEventListener('change', function() {
                dateTo.min = this.value;
            });
            
            dateTo.addEventListener('change', function() {
                if (dateFrom.value && this.value < dateFrom.value) {
                    alert('End date cannot be before start date');
                    this.value = '';
                }
            });
        }
    });
</script>
@endsection