<div class="card shadow-sm border-0">
    <div class="card-body row g-4">
        <div class="col-md-6">
            <label class="form-label">Country Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $destination->name ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $destination->slug ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Flag Image</label>
            <input type="file" name="flag_image" class="form-control" {{ isset($destination) ? '' : 'required' }}>
        </div>
        <div class="col-md-3">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $destination->display_order ?? 0) }}">
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="destination_active" @checked(old('is_active', $destination->is_active ?? true))>
                <label for="destination_active" class="form-check-label">Active</label>
            </div>
        </div>
    </div>
</div>
