@extends('layouts.admin_app')

@section('content')
<style>
    .email-history {
        background: #fff;
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 18px 45px rgba(30, 60, 114, 0.12);
    }

    .email-history__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .email-history__header h2 {
        margin: 0 0 6px;
        font-size: 1.8rem;
        font-weight: 800;
        color: #10213b;
    }

    .email-history__header p {
        margin: 0;
        color: #66758a;
    }

    .email-history__action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 999px;
        background: linear-gradient(135deg, #10213b, #1d4ed8);
        color: #fff;
        text-decoration: none;
        font-weight: 800;
    }

    .email-history__table {
        overflow-x: auto;
    }

    .email-title {
        font-weight: 800;
        color: #1f2f4d;
        margin-bottom: 6px;
    }

    .email-description {
        color: #6c757d;
        line-height: 1.5;
        font-size: 0.92rem;
    }

    .email-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 800;
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
        text-transform: capitalize;
    }

    .email-empty {
        padding: 36px 18px;
        text-align: center;
        color: #6c757d;
    }
</style>

<div class="email-history">
    <div class="email-history__header">
        <div>
            <h2>Email Campaign History</h2>
            <p>Track queued and completed bulk email notifications from the admin panel.</p>
        </div>
        <a href="{{ route('admin.email-notifications.create') }}" class="email-history__action">
            <i class="fas fa-envelope"></i>
            New Email Campaign
        </a>
    </div>

    <div class="email-history__table">
        @if ($emailCampaigns->isEmpty())
            <div class="email-empty">No email campaigns queued yet.</div>
        @else
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Recipients</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Sent By</th>
                        <th>Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emailCampaigns as $campaign)
                        <tr>
                            <td>
                                <div class="email-title">{{ $campaign->title }}</div>
                                <div class="email-description">{{ $campaign->description ?: 'No summary provided.' }}</div>
                            </td>
                            <td>{{ $campaign->total_recipients }}</td>
                            <td>{{ $campaign->sent_count }} sent / {{ $campaign->failed_count }} failed / {{ max($campaign->total_recipients - $campaign->sent_count - $campaign->failed_count, 0) }} pending</td>
                            <td><span class="email-status">{{ str_replace('_', ' ', $campaign->status) }}</span></td>
                            <td>{{ $campaign->admin?->name ?: 'Admin' }}</td>
                            <td>{{ $campaign->completed_at ? $campaign->completed_at->format('d M Y, h:i A') : 'In progress' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $emailCampaigns->links() }}
        @endif
    </div>
</div>
@endsection
