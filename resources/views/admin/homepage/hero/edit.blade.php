@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Hero Section</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.homepage.hero.update') }}" enctype="multipart/form-data" class="card shadow-sm border-0">
        @csrf
        @method('PUT')
        <div class="card-body row g-4">
            <div class="col-md-6">
                <label class="form-label">Badge Text</label>
                <input type="text" name="badge_text" class="form-control" value="{{ old('badge_text', $hero->badge_text) }}">
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check mt-4">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input" id="hero_active" name="is_active" value="1" @checked(old('is_active', $hero->is_active ?? true))>
                    <label class="form-check-label" for="hero_active">Hero Active</label>
                </div>
            </div>
            <div class="col-md-12">
                <label class="form-label">Hero Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $hero->title) }}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Hero Subtitle</label>
                <textarea name="subtitle" class="form-control" rows="4" required>{{ old('subtitle', $hero->subtitle) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Background Image</label>
                <input type="file" name="background_image" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Hero Background Gallery</label>
                <input type="file" name="background_images[]" class="form-control" multiple accept="image/*">
                <small class="text-muted">You can upload multiple hero images here. These images will show on the frontend in a scrollable row.</small>
            </div>
            @if (($hero->images ?? collect())->isNotEmpty())
                <div class="col-12">
                    <div class="row g-3">
                        @foreach ($hero->images as $image)
                            <div class="col-md-3 col-sm-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="{{ \App\Support\PublicAsset::url($image->image_path) }}" alt="Hero gallery image" style="width: 100%; height: 180px; object-fit: cover; border-radius: 16px 16px 0 0;">
                                    <div class="card-body">
                                        <input type="hidden" name="existing_images[{{ $loop->index }}][id]" value="{{ $image->id }}">
                                        <div class="mb-2">
                                            <label class="form-label">Order</label>
                                            <input type="number" name="existing_images[{{ $loop->index }}][sort_order]" class="form-control" value="{{ $image->sort_order }}">
                                        </div>
                                        <div class="form-check">
                                            <input type="hidden" name="existing_images[{{ $loop->index }}][remove]" value="0">
                                            <input type="checkbox" class="form-check-input" id="remove_image_{{ $image->id }}" name="existing_images[{{ $loop->index }}][remove]" value="1">
                                            <label class="form-check-label" for="remove_image_{{ $image->id }}">Remove Image</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="col-md-3">
                <label class="form-label">CTA Text</label>
                <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text', $hero->cta_text) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">CTA Link</label>
                <input type="text" name="cta_link" class="form-control" value="{{ old('cta_link', $hero->cta_link) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Overlay Start Color</label>
                <input type="text" name="overlay_start_color" class="form-control" value="{{ old('overlay_start_color', $hero->overlay_start_color) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Overlay End Color</label>
                <input type="text" name="overlay_end_color" class="form-control" value="{{ old('overlay_end_color', $hero->overlay_end_color) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Overlay Opacity</label>
                <input type="number" step="0.01" min="0" max="1" name="overlay_opacity" class="form-control" value="{{ old('overlay_opacity', $hero->overlay_opacity) }}" required>
            </div>
        </div>

        <div class="card-body border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Floating Flags</h4>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addFlagRow">Add Flag</button>
            </div>
            <div id="flagRows">
                @php $existingFlags = old('flags', $hero->flags->toArray() ?: [[]]); @endphp
                @foreach ($existingFlags as $index => $flag)
                    <div class="row g-3 align-items-end mb-3 flag-row">
                        <div class="col-md-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="flags[{{ $index }}][label]" class="form-control" value="{{ $flag['label'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Top %</label>
                            <input type="number" name="flags[{{ $index }}][position_top]" class="form-control" value="{{ $flag['position_top'] ?? 50 }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Left %</label>
                            <input type="number" name="flags[{{ $index }}][position_left]" class="form-control" value="{{ $flag['position_left'] ?? 50 }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Order</label>
                            <input type="number" name="flags[{{ $index }}][sort_order]" class="form-control" value="{{ $flag['sort_order'] ?? $index }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Flag Image</label>
                            <input type="hidden" name="flags[{{ $index }}][existing_image_path]" value="{{ $flag['image_path'] ?? '' }}">
                            @if (!empty($flag['image_path']))
                                <div class="mb-2">
                                    <img src="{{ \App\Support\PublicAsset::url($flag['image_path']) }}" alt="{{ $flag['label'] ?? 'Flag image' }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 12px; border: 1px solid #ddd; background: #fff; padding: 2px;">
                                </div>
                            @endif
                            <input type="file" name="flag_images[{{ $index }}]" class="form-control">
                            <small class="text-muted">Leave empty to keep the current image.</small>
                        </div>
                        <div class="col-md-1">
                            <div class="form-check mb-2">
                                <input type="hidden" name="flags[{{ $index }}][is_active]" value="0">
                                <input type="checkbox" class="form-check-input" name="flags[{{ $index }}][is_active]" value="1" @checked(($flag['is_active'] ?? true))>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-flag-row">X</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer bg-white">
            <button class="btn btn-primary">Save Hero</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.getElementById('flagRows');
    const addButton = document.getElementById('addFlagRow');

    addButton.addEventListener('click', function () {
        const index = rows.querySelectorAll('.flag-row').length;
        const wrapper = document.createElement('div');
        wrapper.className = 'row g-3 align-items-end mb-3 flag-row';
        wrapper.innerHTML = `
            <div class="col-md-3">
                <label class="form-label">Label</label>
                <input type="text" name="flags[${index}][label]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Top %</label>
                <input type="number" name="flags[${index}][position_top]" class="form-control" value="50">
            </div>
            <div class="col-md-2">
                <label class="form-label">Left %</label>
                <input type="number" name="flags[${index}][position_left]" class="form-control" value="50">
            </div>
            <div class="col-md-2">
                <label class="form-label">Order</label>
                <input type="number" name="flags[${index}][sort_order]" class="form-control" value="${index}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Flag Image</label>
                <input type="hidden" name="flags[${index}][existing_image_path]" value="">
                <input type="file" name="flag_images[${index}]" class="form-control">
                <small class="text-muted">Leave empty to keep the current image.</small>
            </div>
            <div class="col-md-1">
                <div class="form-check mb-2">
                    <input type="hidden" name="flags[${index}][is_active]" value="0">
                    <input type="checkbox" class="form-check-input" name="flags[${index}][is_active]" value="1" checked>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm remove-flag-row">X</button>
            </div>
        `;
        rows.appendChild(wrapper);
    });

    rows.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-flag-row')) {
            event.target.closest('.flag-row').remove();
        }
    });
});
</script>
@endsection
