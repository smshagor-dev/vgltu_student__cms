@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Contact Messages</h2>
        <p>Messages sent from the public Contact Us section appear here so the admin team can review and respond quickly.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Inbox</h3>
                <p>All submitted contact requests in one place.</p>
            </div>
            <span class="admin-chip"><i class="fas fa-envelope"></i> {{ $unreadCount }} unread</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($messages->isEmpty())
            <div class="admin-empty">
                No contact messages received yet.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $index => $message)
                            <tr>
                                <td>{{ $messages->firstItem() + $index }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>
                                    <span class="badge bg-{{ $message->is_read ? 'secondary' : 'warning' }}">
                                        {{ $message->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at?->format('d M Y, h:i A') }}</td>
                                <td class="admin-actions-inline">
                                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-sm btn-primary">View</a>
                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this contact message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $messages->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
