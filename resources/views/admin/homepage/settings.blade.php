@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Website Settings</h2>
        <p>Centralize the public-site identity, navigation helpers, and search interface settings from one structured panel.</p>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.homepage.settings.update') }}" enctype="multipart/form-data" class="admin-form-shell">
        @csrf
        @method('PUT')

        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Brand & Experience Controls</h3>
                <p>Keep homepage content and brand behavior aligned with the new admin layout.</p>
            </div>
            <span class="admin-chip">
                <i class="fas fa-sliders"></i>
                <span>Configuration</span>
            </span>
        </div>

        <div class="admin-grid-2">
            <div class="mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Favicon</label>
                <input type="file" name="favicon" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Button Text</label>
                <input type="text" name="contact_button_text" class="form-control" value="{{ old('contact_button_text', $settings->contact_button_text) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Button Link</label>
                <input type="text" name="contact_button_link" class="form-control" value="{{ old('contact_button_link', $settings->contact_button_link) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Class Routine Text</label>
                <input type="text" name="class_routine_text" class="form-control" value="{{ old('class_routine_text', $settings->class_routine_text) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Class Routine Link</label>
                <input type="text" name="class_routine_link" class="form-control" value="{{ old('class_routine_link', $settings->class_routine_link) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">University Profile Text</label>
                <input type="text" name="university_profile_text" class="form-control" value="{{ old('university_profile_text', $settings->university_profile_text) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">University Profile Link</label>
                <input type="text" name="university_profile_link" class="form-control" value="{{ old('university_profile_link', $settings->university_profile_link) }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Topbar Location</label>
            <input type="text" name="topbar_location" class="form-control" value="{{ old('topbar_location', $settings->topbar_location) }}" required>
        </div>

        <div class="admin-actions-inline">
            <button class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

@endsection
