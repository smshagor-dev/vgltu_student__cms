@extends('layouts.admin_app')

@section('content')
<style>
    .notification-history {
        background: #fff;
        border-radius: 18px;
        padding: 26px;
        box-shadow: 0 18px 45px rgba(30, 60, 114, 0.12);
    }

    .notification-history__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .notification-history__header h2 {
        margin: 0 0 6px;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e3c72;
    }

    .notification-history__header p {
        margin: 0;
        color: #5b6577;
    }

    .notification-history__action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 999px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
    }

    .notification-history__table {
        overflow-x: auto;
    }

    .notification-history__table table {
        width: 100%;
        min-width: 920px;
    }

    .notification-title {
        font-weight: 700;
        color: #1f2f4d;
        margin-bottom: 5px;
    }

    .notification-description {
        color: #6c757d;
        line-height: 1.5;
        font-size: 0.92rem;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .notification-badge--sent {
        background: rgba(42, 82, 152, 0.12);
        color: #1e3c72;
    }

    .notification-badge--read {
        background: rgba(25, 135, 84, 0.14);
        color: #146c43;
    }

    .notification-empty {
        padding: 36px 18px;
        text-align: center;
        color: #6c757d;
    }

</style>

<div class="notification-history">
    <div class="notification-history__header">
        <div>
            <h2>Notification List</h2>
            <p>Review the custom notification history previously sent from the admin panel.</p>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="notification-history__action">
            <i class="fas fa-paper-plane"></i>
            Send New
        </a>
    </div>

    <div class="notification-history__table">
        @if ($notifications->isEmpty())
            <div class="notification-empty">
                No notifications sent yet.
            </div>
        @else
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Message</th>
                        <th>Recipients</th>
                        <th>Read</th>
                        <th>URL</th>
                        <th>Sent By</th>
                        <th>Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td>
                                <div class="notification-title">
                                    @if ($notification->icon)
                                        <i class="{{ $notification->icon }}"></i>
                                    @endif
                                    {{ $notification->title }}
                                </div>
                                <div class="notification-description">
                                    {{ $notification->description ?: 'No description provided.' }}
                                </div>
                            </td>
                            <td>
                                <span class="notification-badge notification-badge--sent">
                                    {{ $notification->recipient_count }} user{{ (int) $notification->recipient_count === 1 ? '' : 's' }}
                                </span>
                            </td>
                            <td>
                                <span class="notification-badge notification-badge--read">
                                    {{ (int) $notification->read_count }} read
                                </span>
                            </td>
                            <td>
                                @if ($notification->url)
                                    <a href="{{ $notification->url }}" target="_blank">{{ $notification->url }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $notification->admin_name ?: 'Admin' }}</td>
                            <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $notifications->links() }}
        @endif
    </div>
</div>
@endsection
