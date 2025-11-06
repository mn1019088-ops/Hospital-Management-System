<div class="mb-3">
    <label class="form-label">Department Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $department->name ?? '') }}" required minlength="3" maxlength="100">
    <div class="invalid-feedback">Please enter a valid department name (min 3 characters).</div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3" maxlength="255">{{ old('description', $department->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Floor</label>
    <input type="number" name="floor" class="form-control" value="{{ old('floor', $department->floor ?? '') }}" min="1" max="50">
    <div class="invalid-feedback">Floor must be between 1 and 50.</div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Contact Email</label>
        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $department->contact_email ?? '') }}">
        <div class="invalid-feedback">Please enter a valid email.</div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Contact Phone</label>
        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $department->contact_phone ?? '') }}">
        <div class="invalid-feedback">Please enter a valid phone number.</div>
    </div>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_active" class="form-check-input" id="isActive{{ $department->id ?? 'new' }}" 
           {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="isActive{{ $department->id ?? 'new' }}">Active</label>
</div>
