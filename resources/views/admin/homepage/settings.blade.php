@extends('layouts.admin_app')

@section('content')
@php
    $footerSocialLinks = old('footer_social_links', $settings->footer_social_links ?? []);
    if (empty($footerSocialLinks)) {
        $footerSocialLinks = [['label' => '', 'url' => '', 'icon_path' => '']];
    }
@endphp
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Frontend Settings</h2>
        <p>Manage the public-site identity, header shortcuts, and footer social links from one structured panel.</p>
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
                <p>Keep frontend content, topbar behavior, and footer social links aligned with the new admin layout.</p>
            </div>
            <span class="admin-chip">
                <i class="fas fa-sliders"></i>
                <span>Frontend</span>
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

        <div class="admin-footer-social card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h4 class="mb-1">Footer Social Icons</h4>
                        <p class="text-muted mb-0">Upload multiple icon images and link each one to a social profile for the public footer.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="addFooterSocialLink">
                        <i class="fas fa-plus"></i>
                        Add Social Icon
                    </button>
                </div>

                <div id="footerSocialLinksList" class="d-grid gap-3">
                    @foreach ($footerSocialLinks as $index => $item)
                        <div class="footer-social-row border rounded-4 p-3" data-social-row>
                            <div class="row g-3 align-items-start">
                                <div class="col-md-3">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="footer_social_links[{{ $index }}][label]" class="form-control" value="{{ $item['label'] ?? '' }}" placeholder="Facebook">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="footer_social_links[{{ $index }}][url]" class="form-control" value="{{ $item['url'] ?? '' }}" placeholder="https://facebook.com/...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Icon Image</label>
                                    <input type="file" name="footer_social_links_icons[{{ $index }}]" class="form-control" accept=".jpg,.jpeg,.png,.gif,.svg,.webp">
                                    <input type="hidden" name="footer_social_links[{{ $index }}][icon_path]" value="{{ $item['icon_path'] ?? '' }}">
                                    @if (!empty($item['icon_path']))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $item['icon_path']) }}" alt="Social icon preview" style="width:40px;height:40px;object-fit:contain;border-radius:10px;border:1px solid #e5e7eb;padding:4px;background:#fff;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-1 d-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-danger mt-md-4" data-remove-social-row>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="admin-actions-inline">
            <button class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

<template id="footerSocialRowTemplate">
    <div class="footer-social-row border rounded-4 p-3" data-social-row>
        <div class="row g-3 align-items-start">
            <div class="col-md-3">
                <label class="form-label">Label</label>
                <input type="text" data-name="label" class="form-control" placeholder="Facebook">
            </div>
            <div class="col-md-5">
                <label class="form-label">Link</label>
                <input type="text" data-name="url" class="form-control" placeholder="https://facebook.com/...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Icon Image</label>
                <input type="file" data-name="icon-file" class="form-control" accept=".jpg,.jpeg,.png,.gif,.svg,.webp">
                <input type="hidden" data-name="icon-path" value="">
            </div>
            <div class="col-md-1 d-flex justify-content-md-end">
                <button type="button" class="btn btn-outline-danger mt-md-4" data-remove-social-row>
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('footerSocialLinksList');
        const addButton = document.getElementById('addFooterSocialLink');
        const template = document.getElementById('footerSocialRowTemplate');

        if (!list || !addButton || !template) {
            return;
        }

        const bindRemoveButtons = function () {
            list.querySelectorAll('[data-remove-social-row]').forEach(function (button) {
                button.onclick = function () {
                    const rows = list.querySelectorAll('[data-social-row]');
                    if (rows.length === 1) {
                        rows[0].querySelectorAll('input').forEach(function (input) {
                            if (input.type === 'file') {
                                input.value = '';
                            } else {
                                input.value = '';
                            }
                        });
                        const image = rows[0].querySelector('img');
                        if (image) {
                            image.remove();
                        }
                        return;
                    }

                    button.closest('[data-social-row]').remove();
                    renumberRows();
                };
            });
        };

        const renumberRows = function () {
            list.querySelectorAll('[data-social-row]').forEach(function (row, index) {
                const label = row.querySelector('[data-name="label"], input[name*="[label]"]');
                const url = row.querySelector('[data-name="url"], input[name*="[url]"]');
                const file = row.querySelector('[data-name="icon-file"], input[type="file"]');
                const path = row.querySelector('[data-name="icon-path"], input[type="hidden"]');

                if (label) {
                    label.name = 'footer_social_links[' + index + '][label]';
                }
                if (url) {
                    url.name = 'footer_social_links[' + index + '][url]';
                }
                if (file) {
                    file.name = 'footer_social_links_icons[' + index + ']';
                }
                if (path) {
                    path.name = 'footer_social_links[' + index + '][icon_path]';
                }
            });
        };

        addButton.addEventListener('click', function () {
            const fragment = template.content.cloneNode(true);
            list.appendChild(fragment);
            renumberRows();
            bindRemoveButtons();
        });

        renumberRows();
        bindRemoveButtons();
    });
</script>
@endsection
