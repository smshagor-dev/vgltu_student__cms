@extends('layouts.admin_app')

@php
    $menuTextField = $pageKey . '_menu_text';
    $titleField = $pageKey . '_title';
    $contentField = $pageKey . '_content';
    $headerField = $pageKey . '_header_path';
    $courses = $courses ?? collect();
@endphp

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>{{ $pageLabel }}</h2>
        <p>Manage the title, header photo, and full page content for the {{ strtolower($pageLabel) }} page from this dedicated CMS screen.</p>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form
        method="POST"
        action="{{ $pageKey === 'about_university' ? route('admin.homepage.pages.about-university.update') : route('admin.homepage.pages.courses.update') }}"
        enctype="multipart/form-data"
        class="admin-form-shell"
    >
        @csrf
        @method('PUT')

        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>{{ $pageLabel }} Content</h3>
                <p>If content stays empty, the frontend page will automatically show a coming soon message.</p>
            </div>
            <span class="admin-chip"><i class="fas fa-pen-ruler"></i> Page Builder</span>
        </div>

        <div class="admin-grid-2">
            <div class="mb-3">
                <label class="form-label">Menu Text</label>
                <input type="text" name="menu_text" class="form-control" value="{{ old('menu_text', $settings->{$menuTextField}) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Page Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $settings->{$titleField}) }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Header Photo</label>
            <input type="file" name="header_image" class="form-control">
            @if (!empty($settings->{$headerField}))
                <div class="mt-3">
                    <img src="{{ \App\Support\PublicAsset::url($settings->{$headerField}) }}" alt="{{ $pageLabel }} Header" style="max-width: 340px; width: 100%; border-radius: 18px; border: 1px solid rgba(148, 163, 184, 0.2);">
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label class="form-label">Page Content</label>
            <textarea name="content" id="page_content" class="form-control" rows="12">{{ old('content', $settings->{$contentField}) }}</textarea>
        </div>

        <div class="admin-actions-inline">
            <button class="btn btn-primary">Save {{ $pageLabel }}</button>
        </div>
    </form>

    @if ($pageKey === 'courses')
        <section class="admin-panel">
            <div class="admin-toolbar">
                <div class="admin-toolbar__title">
                    <h3>Course Cards</h3>
                    <p>Add individual courses here. Frontend course page will show them as clickable cards.</p>
                </div>
                <a href="{{ route('admin.homepage.courses.create') }}" class="btn btn-primary">Add Course</a>
            </div>

            <div class="admin-table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->slug }}</td>
                                <td>{{ $course->display_order }}</td>
                                <td>{{ $course->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.homepage.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.homepage.courses.destroy', $course) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this course?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">No courses added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
</div>

<script src="https://cdn.tiny.cloud/1/wj0tnfge5dwt2pfaan81gg68pfs8bqtzmjrn9k5kxmwaqb0e/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#page_content',
        height: 380,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
    });
</script>
@endsection
