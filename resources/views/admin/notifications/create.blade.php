@extends('layouts.admin_app')

@section('content')
<style>
    .notification-card {
        max-width: 920px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 18px 45px rgba(30, 60, 114, 0.12);
    }

    .notification-card__heading h2 {
        margin-bottom: 8px;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e3c72;
    }

    .notification-card__heading p {
        margin-bottom: 24px;
        color: #5b6577;
    }

    .notification-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .notification-field {
        margin-bottom: 18px;
    }

    .notification-field--full {
        grid-column: 1 / -1;
    }

    .notification-field label {
        display: block;
        margin-bottom: 8px;
        color: #1f2f4d;
        font-weight: 600;
    }

    .notification-field .form-control,
    .notification-field .form-select {
        min-height: 48px;
        border-radius: 12px;
    }

    .notification-help {
        margin-top: 6px;
        font-size: 0.88rem;
        color: #6c757d;
    }

    .notification-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .notification-submit {
        border: 0;
        border-radius: 999px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        font-weight: 700;
    }

    @media (max-width: 767px) {
        .notification-card {
            padding: 20px;
        }

        .notification-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="notification-card">
    <div class="notification-card__heading">
        <h2>Send Notification</h2>
        <p>Send a portal notification from the admin panel to all users or to one specific user.</p>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary">View Notification List</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf

        <div class="notification-grid">
            <div class="notification-field">
                <label for="recipient_type">Recipient Type</label>
                <select name="recipient_type" id="recipient_type" class="form-select">
                    <option value="all" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="single" {{ old('recipient_type', $selectedUser ? 'single' : 'all') === 'single' ? 'selected' : '' }}>Single User</option>
                </select>
            </div>

            <div class="notification-field" id="singleUserField">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Choose a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (string) old('user_id', $selectedUser?->id) === (string) $user->id ? 'selected' : '' }}>
                            {{ $user->full_name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="notification-field notification-field--full">
                <label for="title">Notification Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Important update for students" required>
            </div>

            <div class="notification-field notification-field--full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Write the full message here...">{{ old('description') }}</textarea>
            </div>

            <div class="notification-field">
                <label for="url">Open URL</label>
                <input type="text" name="url" id="url" class="form-control" value="{{ old('url', route('home')) }}" placeholder="{{ route('home') }}">
                <div class="notification-help">Users will be taken to this link when they open the notification.</div>
            </div>

            <div class="notification-field">
                <label for="icon">Icon Class</label>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', 'fas fa-bell') }}" placeholder="fas fa-bell">
                <div class="notification-help">You can use any valid Font Awesome icon class here.</div>
            </div>
        </div>

        <div class="notification-actions">
            <button type="submit" class="notification-submit">Send Notification</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipientType = document.getElementById('recipient_type');
        const singleUserField = document.getElementById('singleUserField');

        const syncRecipientField = function () {
            if (!recipientType || !singleUserField) {
                return;
            }

            const isSingle = recipientType.value === 'single';
            singleUserField.style.display = isSingle ? 'block' : 'none';
        };

        syncRecipientField();

        if (recipientType) {
            recipientType.addEventListener('change', syncRecipientField);
        }
    });
</script>
@endsection
