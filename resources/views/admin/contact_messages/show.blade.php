@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>{{ $contactMessage->subject }}</h2>
        <p>Review the full message details submitted from the website contact form.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Message Details</h3>
                <p>Submitted on {{ $contactMessage->created_at?->format('d M Y, h:i A') }}</p>
            </div>
            <div class="admin-actions-inline">
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-primary">Back to Inbox</a>
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this contact message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>

        <div class="admin-grid-2">
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span>Name</span>
                    <strong>{{ $contactMessage->name }}</strong>
                </div>
                <div class="admin-kv-item">
                    <span>Email</span>
                    <strong><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></strong>
                </div>
                <div class="admin-kv-item">
                    <span>Subject</span>
                    <strong>{{ $contactMessage->subject }}</strong>
                </div>
                <div class="admin-kv-item">
                    <span>Status</span>
                    <strong>{{ $contactMessage->is_read ? 'Read' : 'Unread' }}</strong>
                </div>
            </div>

            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span>Message</span>
                    <strong style="white-space: pre-wrap;">{{ $contactMessage->message }}</strong>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
