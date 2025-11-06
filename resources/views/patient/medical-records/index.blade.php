@extends('layouts.patient')

@section('title', 'My Medical Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Medical Records</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-primary fs-6">
                Total Records: {{ $medicalRecords->total() }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalRecords = $medicalRecords->total();
            $recentRecords = $medicalRecords->where('record_date', '>=', now()->subDays(30))->count();
            $currentYearRecords = $medicalRecords->where('record_date', '>=', now()->startOfYear())->count();
        @endphp

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Last 30 Days
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Year
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $currentYearRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-medical me-2"></i>Medical Records List
                <span class="badge bg-light text-primary ms-2">{{ $medicalRecords->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Showing {{ $medicalRecords->firstItem() ?? 0 }}-{{ $medicalRecords->lastItem() ?? 0 }} of {{ $medicalRecords->total() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($medicalRecords->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Record ID</th>
                            <th class="border-0">Doctor</th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Diagnosis</th>
                            <th class="border-0">Treatment</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $record->record_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder bg-info rounded-circle me-3">
                                        <i class="fas fa-user-md text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Dr. {{ $record->doctor->name }}</div>
                                        <small class="text-muted">{{ $record->doctor->specialization }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $record->record_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $record->record_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $record->diagnosis }}">
                                    {{ $record->diagnosis }}
                                </span>
                            </td>
                            <td>
                                @if($record->treatment)
                                <span class="d-inline-block text-truncate" style="max-width: 150px;" 
                                      title="{{ $record->treatment }}">
                                    {{ $record->treatment }}
                                </span>
                                @else
                                <span class="text-muted">No treatment recorded</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <a href="{{ route('patient.medical-records.show', $record) }}" 
                                       class="btn btn-info" title="View Details" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Print Button -->
                                    <button class="btn btn-secondary" 
                                            onclick="printRecord('{{ $record->record_id }}')"
                                            title="Print Record" data-bs-toggle="tooltip">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>

                                <!-- Quick View Modal -->
                                <div class="modal fade" id="quickViewModal{{ $record->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-file-medical me-2"></i>Medical Record Preview
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="border-bottom pb-2">Record Information</h6>
                                                        <p><strong>Record ID:</strong> {{ $record->record_id }}</p>
                                                        <p><strong>Date:</strong> {{ $record->record_date->format('M d, Y') }}</p>
                                                        <p><strong>Doctor:</strong> Dr. {{ $record->doctor->name }}</p>
                                                        <p><strong>Specialization:</strong> {{ $record->doctor->specialization }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="border-bottom pb-2">Medical Details</h6>
                                                        <p><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                                                        @if($record->treatment)
                                                        <p><strong>Treatment:</strong> {{ $record->treatment }}</p>
                                                        @endif
                                                        @if($record->symptoms)
                                                        <p><strong>Symptoms:</strong> {{ $record->symptoms }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Close
                                                </button>
                                                <a href="{{ route('patient.medical-records.show', $record) }}" class="btn btn-primary">
                                                    <i class="fas fa-file-medical me-1"></i>View Full Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $medicalRecords->firstItem() }} to {{ $medicalRecords->lastItem() }} of {{ $medicalRecords->total() }} entries
                </div>
                <div>
                    {{ $medicalRecords->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-file-medical-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Medical Records Found</h4>
                <p class="text-muted mb-4">Your medical records will appear here after your appointments and consultations with doctors.</p>
                <div class="mt-3">
                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-primary me-2">
                        <i class="fas fa-calendar-alt me-1"></i>View Appointments
                    </a>
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Book Appointment
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Additional Information Section -->
    @if($medicalRecords->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>About Your Medical Records
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                                <h6>Secure & Private</h6>
                                <p class="small text-muted">Your medical records are protected and only accessible to you and authorized medical staff.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-history fa-2x text-success mb-2"></i>
                                <h6>Complete History</h6>
                                <p class="small text-muted">Maintain a complete history of your diagnoses, treatments, and medical consultations.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-download fa-2x text-warning mb-2"></i>
                                <h6>Easy Access</h6>
                                <p class="small text-muted">View and manage your medical records anytime, anywhere through your patient portal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
        padding: 1rem 0.75rem;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .patient-card {
        transition: transform 0.2s;
    }

    .patient-card:hover {
        transform: translateY(-2px);
    }
</style>

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
    });

    function printRecord(recordId) {
        // Create a print-friendly version of the record
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Medical Record - ${recordId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                        .section { margin-bottom: 15px; }
                        .section-title { font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px; }
                        .record-details { width: 100%; border-collapse: collapse; }
                        .record-details td { padding: 8px; border-bottom: 1px solid #eee; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Medical Record</h2>
                        <h3>Record ID: ${recordId}</h3>
                        <p>Printed on: ${new Date().toLocaleDateString()}</p>
                    </div>
                    <div class="footer">
                        <p>This is a computer-generated record. For official purposes, please contact the hospital.</p>
                    </div>
                    <div class="no-print" style="text-align: center; margin-top: 20px;">
                        <button onclick="window.print()">Print This Page</button>
                        <button onclick="window.close()">Close</button>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
    }

    function quickView(recordId) {
        // This function would typically fetch record details via AJAX
        // For now, we'll use the modal approach
        const modal = new bootstrap.Modal(document.getElementById('quickViewModal' + recordId));
        modal.show();
    }
</script>
@endsection
@endsection