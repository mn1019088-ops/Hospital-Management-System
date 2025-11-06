@extends('layouts.reception')

@section('title', 'Allocate Room to Patient')
@section('page-title', 'Allocate Room to Patient')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reception.room-allocations') }}">Room Allocations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Allocation</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-bed me-2"></i>Allocate Room to Patient
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

                    <form method="POST" action="{{ route('reception.room-allocations.store') }}" id="allocationForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Patient Selection -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="patient_id" class="form-label required">Patient</label>
                                    <select name="patient_id" id="patient_id" class="form-control select2 @error('patient_id') is-invalid @enderror" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" 
                                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}
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
                                    <div id="patient-info" class="mt-2 p-2 bg-light rounded" style="display: none;">
                                        <small>
                                            <strong>Gender:</strong> <span id="patient-gender"></span> | 
                                            <strong>Age:</strong> <span id="patient-age"></span> | 
                                            <strong>Phone:</strong> <span id="patient-phone"></span>
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
                                                {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->room_type }} 
                                                ({{ $room->capacity - $room->occupied }} beds available) 
                                                - ₹{{ number_format($room->price_per_day, 2) }}/day
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="room-info" class="mt-2 p-2 bg-light rounded" style="display: none;">
                                        <small>
                                            <strong>Type:</strong> <span id="room-type"></span> | 
                                            <strong>Available Beds:</strong> <span id="room-available"></span>/<span id="room-capacity"></span> |
                                            <strong>Price:</strong> ₹<span id="room-price"></span>/day
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
                                           value="{{ old('admission_date', date('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d') }}" 
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
                                           value="{{ old('estimated_stay_days', 1) }}" 
                                           min="1" max="365" required>
                                    @error('estimated_stay_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        <input type="text" id="total_amount" class="form-control bg-light" readonly value="0.00">
                                        <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
                                    </div>
                                    <small class="form-text text-muted">Calculated automatically: (Room Price × Estimated Days)</small>
                                </div>
                            </div>
                            
                            <!-- Estimated Discharge Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_discharge_date" class="form-label">Estimated Discharge Date</label>
                                    <input type="text" id="estimated_discharge_date" class="form-control bg-light" readonly value="Not calculated">
                                </div>
                            </div>
                        </div>

                        <!-- Reason for Admission -->
                        <div class="form-group mb-3">
                            <label for="reason" class="form-label required">Reason for Admission</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" 
                                      rows="3" placeholder="Enter detailed reason for admission..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-group mb-4">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Summary Card -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Allocation Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Patient:</strong>
                                        <div id="summary-patient" class="text-muted">Not selected</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Room:</strong>
                                        <div id="summary-room" class="text-muted">Not selected</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Duration:</strong>
                                        <div id="summary-duration" class="text-muted">0 days</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Amount:</strong>
                                        <div id="summary-total" class="fw-bold text-success">₹0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fas fa-bed me-2"></i> Allocate Room
                                    </button>
                                    <a href="{{ route('reception.room-allocations') }}" class="btn btn-danger btn-lg">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted"><i class="fas fa-info-circle"></i> All fields marked with <span class="text-danger">*</span> are required</small>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
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
    
    .badge-admitted {
        background-color: #dc3545;
        color: white;
    }
    
    .badge-available {
        background-color: #28a745;
        color: white;
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
            const patientStatus = option.data('status');
            
            if(option.val()){
                $('#patient-gender').text(option.data('gender') || 'N/A');
                $('#patient-age').text(option.data('age') || 'N/A');
                $('#patient-phone').text(option.data('phone') || 'N/A');
                
                // Update status badge
                const statusBadge = $('#patient-status');
                statusBadge.text(patientStatus || 'N/A');
                statusBadge.removeClass('badge-admitted badge-available');
                
                if (patientStatus === 'admitted') {
                    statusBadge.addClass('badge-admitted');
                    $('#patient-warning').show();
                    $('#warning-text').text('This patient is already admitted. Cannot allocate new room until discharged.');
                    $('#submitBtn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                } else {
                    statusBadge.addClass('badge-available');
                    $('#patient-warning').hide();
                    $('#submitBtn').prop('disabled', false).addClass('btn-primary').removeClass('btn-secondary');
                }
                
                $('#patient-info').show();
            } else { 
                $('#patient-info').hide(); 
                $('#patient-warning').hide();
                $('#submitBtn').prop('disabled', false).addClass('btn-primary').removeClass('btn-secondary');
            }
            updateSummary();
        });

        // Room selection change handler
        $('#room_id').on('change', function() {
            const option = $(this).find('option:selected');
            if(option.val()){
                const availableBeds = parseInt(option.data('available')) || 0;
                const totalBeds = parseInt(option.data('total-beds')) || 0;
                
                $('#room-type').text(option.data('type') || 'N/A');
                $('#room-available').text(availableBeds);
                $('#room-total-beds').text(totalBeds);
                $('#room-price').text(option.data('price') ? parseFloat(option.data('price')).toFixed(2) : 'N/A');
                
                // Show warning if no beds available
                if (availableBeds <= 0) {
                    $('#room-warning').show();
                    $('#room-warning-text').text('No beds available in this room. Please select a different room.');
                    $('#submitBtn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                } else {
                    $('#room-warning').hide();
                    // Only enable if patient is not already admitted
                    const patientStatus = $('#patient_id option:selected').data('status');
                    if (patientStatus !== 'admitted') {
                        $('#submitBtn').prop('disabled', false).addClass('btn-primary').removeClass('btn-secondary');
                    }
                }
                
                $('#room-info').show();
            } else { 
                $('#room-info').hide(); 
                $('#room-warning').hide();
                // Only enable if patient is not already admitted
                const patientStatus = $('#patient_id option:selected').data('status');
                if (patientStatus !== 'admitted') {
                    $('#submitBtn').prop('disabled', false).addClass('btn-primary').removeClass('btn-secondary');
                }
            }
            calculateTotalAmount();
            updateSummary();
        });

        // Admission date and estimated days change handlers
        $('#admission_date, #estimated_stay_days').on('input change', function() {
            calculateTotalAmount();
        });

        // Form submission handler
        $('#allocationForm').on('submit', function(e) {
            const patientStatus = $('#patient_id option:selected').data('status');
            const availableBeds = parseInt($('#room_id option:selected').data('available')) || 0;
            
            // Final validation before submission
            if (patientStatus === 'admitted') {
                e.preventDefault();
                toastr.error('This patient is already admitted. Please discharge the patient first.', 'Validation Error!');
                return false;
            }
            
            if (availableBeds <= 0) {
                e.preventDefault();
                toastr.error('Selected room has no available beds. Please choose a different room.', 'Validation Error!');
                return false;
            }
            
            // Update the hidden total amount field with the calculated value
            const total = calculateTotalAmount();
            $('#total_amount_hidden').val(total.toFixed(2));
            
            // Disable submit button to prevent double submission
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Allocating...');
        });

        // Initialize on page load
        if($('#patient_id').val()) $('#patient_id').trigger('change');
        if($('#room_id').val()) $('#room_id').trigger('change');
        calculateTotalAmount();
        updateSummary();
    });
</script>
@endpush