@extends('layouts.reception')

@section('title', 'Add New Room')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add New Room</h1>
        <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Rooms
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-plus me-2"></i> Room Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reception.rooms.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                           id="room_number" name="room_number" value="{{ old('room_number') }}" 
                                           required maxlength="20" placeholder="e.g., 101, A-201">
                                    @error('room_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                        <option value="">Select Room Type</option>
                                        @foreach(['general', 'private', 'semi-private', 'deluxe', 'icu', 'operation', 'emergency', 'maternity', 'recovery'] as $type)
                                            <option value="{{ $type }}" {{ old('room_type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('-', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select class="form-control @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Floor <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                           id="floor" name="floor" value="{{ old('floor') }}" 
                                           required min="1" max="50" placeholder="Floor number">
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity', 1) }}" 
                                           required min="1" max="20" placeholder="Number of patients">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_per_day" class="form-label">Price per Day (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                                           id="price_per_day" name="price_per_day" value="{{ old('price_per_day') }}" 
                                           step="0.01" required min="0" max="100000" placeholder="0.00">
                                    @error('price_per_day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="cleaning" {{ old('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="facilities" class="form-label">Facilities</label>
                            <textarea class="form-control @error('facilities') is-invalid @enderror" id="facilities" 
                                      name="facilities" rows="3" maxlength="500" 
                                      placeholder="Describe room facilities (AC, TV, Private Bathroom, etc.)">{{ old('facilities') }}</textarea>
                            @error('facilities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Add Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Room Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Room numbers must be unique</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Select appropriate room type</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Assign to correct department</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Set realistic capacity (1-20 patients)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Set appropriate pricing</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Update status accurately</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection