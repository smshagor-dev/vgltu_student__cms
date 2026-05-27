@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>SMTP Settings</h2>
        <p>Configure the outgoing mail server used for registration, approval, rejection, and campaign emails.</p>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.smtp.update') }}" class="admin-form-shell">
        @csrf
        @method('PUT')

        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Mail Delivery</h3>
                <p>Enable SMTP and add your mail provider credentials.</p>
            </div>
            <span class="admin-chip"><i class="fas fa-envelope"></i> Mail Config</span>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input type="hidden" name="smtp_enabled" value="0">
                <input class="form-check-input" type="checkbox" name="smtp_enabled" value="1" id="smtp_enabled" @checked(old('smtp_enabled', $settings->smtp_enabled))>
                <label class="form-check-label" for="smtp_enabled">Enable SMTP Mail Sending</label>
            </div>
        </div>

        <div class="admin-grid-2">
            <div class="mb-3">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $settings->smtp_host) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Port</label>
                <input type="number" name="smtp_port" class="form-control" value="{{ old('smtp_port', $settings->smtp_port) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Username</label>
                <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username', $settings->smtp_username) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Password</label>
                <input type="password" name="smtp_password" class="form-control" value="">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Encryption</label>
                <input type="text" name="smtp_encryption" class="form-control" value="{{ old('smtp_encryption', $settings->smtp_encryption) }}" placeholder="tls">
            </div>
            <div class="mb-3">
                <label class="form-label">From Address</label>
                <input type="email" name="smtp_from_address" class="form-control" value="{{ old('smtp_from_address', $settings->smtp_from_address) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">From Name</label>
                <input type="text" name="smtp_from_name" class="form-control" value="{{ old('smtp_from_name', $settings->smtp_from_name) }}">
            </div>
        </div>

        <div class="admin-actions-inline">
            <button class="btn btn-primary">Save SMTP Settings</button>
        </div>
    </form>
</div>
@endsection
