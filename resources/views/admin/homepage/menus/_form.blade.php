<div class="card shadow-sm border-0">
    <div class="card-body row g-4">
        <div class="col-md-6">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $menu->title ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">URL</label>
            <input type="text" name="url" class="form-control" value="{{ old('url', $menu->url ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Parent Menu</label>
            <select name="parent_id" class="form-select">
                <option value="">Root Menu</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $menu->parent_id ?? '') == $parent->id)>{{ $parent->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $menu->sort_order ?? 0) }}">
        </div>
        <div class="col-md-2 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $menu->is_active ?? true))>
                <label for="is_active" class="form-check-label">Active</label>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="hidden" name="open_in_new_tab" value="0">
                <input type="checkbox" name="open_in_new_tab" value="1" class="form-check-input" id="open_in_new_tab" @checked(old('open_in_new_tab', $menu->open_in_new_tab ?? false))>
                <label for="open_in_new_tab" class="form-check-label">New Tab</label>
            </div>
        </div>
    </div>
</div>
