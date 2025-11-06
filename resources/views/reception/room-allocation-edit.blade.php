@extends('layouts.reception')

@section('title', 'Edit Room Allocation')
@section('page-title', 'Edit Room Allocation')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reception.room-allocations') }}">Room Allocations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Allocation</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>Edit Room Allocation
                    </h3>
                    <a href="{{ route('reception.room-allocations') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Allocations
                    </a>
                </div>
                <div class="card-body">
                    <!-- Toastr Notifications -->
                    @if(session('success'))
                    <script>
                        toastr.success('{{ session('success') }}', 'Success!');
                    </script>
                    @endif

                    @if(session('error'))
                    <script>
                        toastr.error('{{ session('error') }}', 'Error!');
                    </script>
                    @endif

                    @if($errors->any())
                    <script>
                        toastr.error('{{ $errors->first() }}', 'Validation Error!');
                    </script>
                    @endif

                    <!-- Display validation errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reception.room-allocations.update', $allocation->id) }}" id="editAllocationForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Patient Selection -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="patient_id" class="form-label required">Patient</label>
                                    <select name="patient_id" id="patient_id" class="form-control select2 @error('patient_id') is-invalid @enderror" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" 
                                                {{ old('patient_id', $allocation->patient_id) == $patient->id ? 'selected' : '' }}
                                                data-gender="{{ $patient->gender }}"
                                                data-age="{{ $patient->age }}"
                                                data-phone="{{ $patient->phone }}">
                                                {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id ?? $patient->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="patient-info" class="mt-2 p-2 bg-light rounded">
                                        <small>
                                            <strong>Gender:</strong> <span id="patient-gender">{{ $allocation->patient->gender ?? 'N/A' }}</span> | 
                                            <strong>Age:</strong> <span id="patient-age">{{ $allocation->patient->age ?? 'N/A' }}</span> | 
                                            <strong>Phone:</strong> <span id="patient-phone">{{ $allocation->patient->phone ?? 'N/A' }}</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Room Selection -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="room_id" class="form-label required">Room</label>
                                    <select name="room_id" id="room_id" class="form-control select2 @error('room_id') is-invalid @enderror" required>
                                        <option value="">Select Room</option>
                                        @foreach($availableRooms as $room)
                                            <option value="{{ $room->id }}" 
                                                data-price="{{ $room->price_per_day }}"
                                                data-available="{{ $room->capacity - $room->occupied }}"
                                                data-type="{{ $room->room_type }}"
                                                data-capacity="{{ $room->capacity }}"
                                                data-occupied="{{ $room->occupied }}"
                                                {{ old('room_id', $allocation->room_id) == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->room_type }} 
                                                ({{ $room->capacity - $room->occupied }} beds available) 
                                                - ₹{{ number_format($room->price_per_day, 2) }}/day
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="room-info" class="mt-2 p-2 bg-light rounded">
                                        <small>
                                            <strong>Type:</strong> <span id="room-type">{{ $allocation->room->room_type ?? 'N/A' }}</span> | 
                                            <strong>Available Beds:</strong> <span id="room-available">{{ ($allocation->room->capacity - $allocation->room->occupied) ?? 'N/A' }}</span>/<span id="room-capacity">{{ $allocation->room->capacity ?? 'N/A' }}</span> |
                                            <strong>Price:</strong> ₹<span id="room-price">{{ number_format($allocation->room->price_per_day, 2) ?? 'N/A' }}</span>/day
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Admission Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="admission_date" class="form-label required">Admission Date</label>
                                    <input type="date" name="admission_date" id="admission_date" 
                                           class="form-control @error('admission_date') is-invalid @enderror" 
                                           value="{{ old('admission_date', $allocation->admission_date->format('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d', strtotime('-1 year')) }}" 
                                           max="{{ date('Y-m-d', strtotime('+1 year')) }}" 
                                           required>
                                    @error('admission_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Estimated Stay Days -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_stay_days" class="form-label required">Estimated Stay (Days)</label>
                                    <input type="number" name="estimated_stay_days" id="estimated_stay_days" 
                                           class="form-control @error('estimated_stay_days') is-invalid @enderror" 
                                           value="{{ old('estimated_stay_days', $allocation->estimated_stay_days) }}" 
                                           min="1" max="365" 
                                           placeholder="Enter number of days"
                                           required>
                                    @error('estimated_stay_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Estimated number of days the patient will stay
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Total Amount Display -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_amount" class="form-label">Estimated Total Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="text" id="total_amount" class="form-control bg-light" readonly 
                                               value="{{ number_format($allocation->total_amount, 2) }}">
                                        <input type="hidden" name="total_amount" id="total_amount_hidden" value="{{ $allocation->total_amount }}">
                                    </div>
                                    <small class="form-text text-muted">
                                        Calculated automatically: (Room Price × Estimated Days)
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Estimated Discharge Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_discharge_date" class="form-label">Estimated Discharge Date</label>
                                    <input type="text" id="estimated_discharge_date" class="form-control bg-light" readonly 
                                           value="{{ $allocation->estimated_discharge_date ? $allocation->estimated_discharge_date->format('M d, Y') : 'Not calculated' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Reason for Admission -->
                        <div class="form-group mb-3">
                            <label for="reason" class="form-label required">Reason for Admission</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" 
                                      rows="3" placeholder="Enter detailed reason for admission..." required>{{ old('reason', $allocation->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-group mb-4">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="2" placeholder="Any additional notes, special requirements, or instructions...">{{ old('notes', $allocation->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Summary Card -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Allocation Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Patient:</strong>
                                        <div id="summary-patient" class="text-secondary">{{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Room:</strong>
                                        <div id="summary-room" class="text-secondary">{{ $allocation->room->room_number }} - {{ $allocation->room->room_type }}</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Duration:</strong>
                                        <div id="summary-duration" class="text-secondary">{{ $allocation->estimated_stay_days }} days</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Amount:</strong>
                                        <div id="summary-total" class="text-success fw-bold">₹{{ number_format($allocation->total_amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Paid Amount:</strong>
                                        <div class="text-success">₹{{ number_format($allocation->paid_amount, 2) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Due Amount:</strong>
                                        <div class="text-danger fw-bold">₹{{ number_format($allocation->total_amount - $allocation->paid_amount, 2) }}</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <span class="badge {{ $allocation->status == 'active' ? 'bg-success' : ($allocation->status == 'discharged' ? 'bg-info' : 'bg-warning') }}">
                                            {{ ucfirst($allocation->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fas fa-save me-2"></i> Update Allocation
                                    </button>
                                    <a href="{{ route('reception.room-allocations') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                    @if($allocation->status === 'active')
                                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash me-2"></i> Delete
                                        </button>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> All fields marked with <span class="text-danger">*</span> are required
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($allocation->status === 'active')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room allocation?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. The room will be made available again and all allocation data will be permanently deleted.
                </div>
                <div class="border p-3 rounded">
                    <p><strong>Patient:</strong> {{ $allocation->patient->first_name }} {{ $allocation->patient->last_name }}</p>
                    <p><strong>Room:</strong> {{ $allocation->room->room_number }} - {{ $allocation->room->room_type }}</p>
                    <p><strong>Allocation ID:</strong> {{ $allocation->allocation_id }}</p>
                    <p><strong>Admission Date:</strong> {{ $allocation->admission_date->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('reception.room-allocations.destroy', $allocation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i> Delete Allocation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ 
            placeholder: 'Select an option', 
            allowClear: true, 
            width: '100%' 
        });

        // Function to calculate total amount and discharge date
        function calculateTotalAmount() {
            const roomOption = $('#room_id option:selected');
            const price = parseFloat(roomOption.data('price')) || 0;
            const days = parseInt($('#estimated_stay_days').val()) || 0;
            const total = price * days;
            
            // Calculate discharge date
            const admissionDate = $('#admission_date').val();
            if (admissionDate && days > 0) {
                const dischargeDate = new Date(admissionDate);
                dischargeDate.setDate(dischargeDate.getDate() + days);
                $('#estimated_discharge_date').val(dischargeDate.toISOString().split('T')[0]);
            } else {
                $('#estimated_discharge_date').val('Not calculated');
            }
            
            // Update display fields
            $('#total_amount').val(total.toFixed(2));
            $('#total_amount_hidden').val(total.toFixed(2));
            $('#summary-total').text('₹' + total.toFixed(2));
            $('#summary-duration').text(days + ' day' + (days !== 1 ? 's' : ''));
            
            return total;
        }

        // Function to update summary
        function updateSummary() {
            // Update patient summary
            const patientText = $('#patient_id option:selected').text() || 'Not selected';
            $('#summary-patient').text(patientText).toggleClass('text-muted', !$('#patient_id').val()).toggleClass('text-secondary', !!$('#patient_id').val());
            
            // Update room summary
            const roomText = $('#room_id option:selected').text().split(' - ')[0] || 'Not selected';
            $('#summary-room').text(roomText).toggleClass('text-muted', !$('#room_id').val()).toggleClass('text-secondary', !!$('#room_id').val());
        }

        // Patient selection change handler
        $('#patient_id').on('change', function() {
            const option = $(this).find('option:selected');
            if(option.val()){
                $('#patient-gender').text(option.data('gender') || 'N/A');
                $('#patient-age').text(option.data('age') || 'N/A');
                $('#patient-phone').text(option.data('phone') || 'N/A');
            }
            updateSummary();
        });

        // Room selection change handler
        $('#room_id').on('change', function() {
            const option = $(this).find('option:selected');
            if(option.val()){
                const availableBeds = parseInt(option.data('available')) || 0;
                const totalBeds = parseInt(option.data('capacity')) || 0;
                
                $('#room-type').text(option.data('type') || 'N/A');
                $('#room-available').text(availableBeds);
                $('#room-capacity').text(totalBeds);
                $('#room-price').text(option.data('price') ? parseFloat(option.data('price')).toFixed(2) : 'N/A');
            }
            calculateTotalAmount();
            updateSummary();
        });

        // Admission date and estimated days change handlers
        $('#admission_date, #estimated_stay_days').on('input change', function() {
            calculateTotalAmount();
        });

        // Form submission handler
        $('#editAllocationForm').on('submit', function() {
            // Update the hidden total amount field with the calculated value
            const total = calculateTotalAmount();
            $('#total_amount_hidden').val(total.toFixed(2));
            
            // Disable submit button to prevent double submission
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Updating...');
        });

        // Initialize on page load
        if($('#patient_id').val()) $('#patient_id').trigger('change');
        if($('#room_id').val()) $('#room_id').trigger('change');
        calculateTotalAmount();
        updateSummary();
    });
</script>
@endpush