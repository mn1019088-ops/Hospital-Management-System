@extends('layouts.reception')

@section('title', 'Edit Room')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Room - {{ $room->room_number }}</h1>
        <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Rooms
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-edit me-2"></i> Edit Room Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reception.rooms.update', $room->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                           id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" 
                                           required maxlength="20">
                                    @error('room_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                        @foreach(['general', 'private', 'semi-private', 'deluxe', 'icu', 'operation', 'emergency', 'maternity', 'recovery'] as $type)
                                            <option value="{{ $type }}" {{ old('room_type', $room->room_type) == $type ? 'selected' : '' }}>
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
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $room->department_id) == $department->id ? 'selected' : '' }}>
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
                                           id="floor" name="floor" value="{{ old('floor', $room->floor) }}" 
                                           required min="1" max="50">
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
                                           id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" 
                                           required min="1" max="20">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_per_day" class="form-label">Price per Day (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                                           id="price_per_day" name="price_per_day" value="{{ old('price_per_day', $room->price_per_day) }}" 
                                           step="0.01" required min="0" max="100000">
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
                                        @foreach(['available', 'occupied', 'maintenance', 'cleaning'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $room->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
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
                                      name="facilities" rows="3" maxlength="500">{{ old('facilities', $room->facilities) }}</textarea>
                            @error('facilities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reception.rooms') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i> Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Room Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Room Number:</strong> {{ $room->room_number }}</p>
                    <p><strong>Current Type:</strong> {{ ucfirst(str_replace('-', ' ', $room->room_type)) }}</p>
                    <p><strong>Department:</strong> {{ $room->department->name ?? 'N/A' }}</p>
                    <p><strong>Floor:</strong> {{ $room->floor }}</p>
                    <p><strong>Capacity:</strong> {{ $room->capacity }}</p>
                    <p><strong>Current Status:</strong> 
                        <span class="badge {{ $room->status == 'available' ? 'bg-success' : ($room->status == 'occupied' ? 'bg-warning text-dark' : ($room->status == 'maintenance' ? 'bg-danger' : 'bg-info')) }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </p>
                    <p><strong>Price per Day:</strong> ₹{{ number_format($room->price_per_day, 2) }}</p>
                    <p><strong>Last Updated:</strong> {{ $room->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection