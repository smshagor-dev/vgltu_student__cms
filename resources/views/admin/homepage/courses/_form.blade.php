<div class="card shadow-sm border-0">
    <div class="card-body row g-4">
        <div class="col-md-6">
            <label class="form-label">Course Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $course->title ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $course->slug ?? '') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $course->display_order ?? 0) }}">
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="course_active" @checked(old('is_active', $course->is_active ?? true))>
                <label for="course_active" class="form-check-label">Active</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Course Description</label>
            <textarea name="description" id="course_description" class="form-control" rows="12">{{ old('description', $course->description ?? '') }}</textarea>
        </div>
    </div>
</div>
