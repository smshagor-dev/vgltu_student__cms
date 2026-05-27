@extends('layouts.admin_app')

@section('content')
<style>
    .room-detail-page {
        display: grid;
        gap: 24px;
    }

    .room-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 28px;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(34, 197, 94, 0.22), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1f2937 52%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
    }

    .room-detail-header h2 {
        color: #fff;
        margin-bottom: 8px;
    }

    .room-detail-header p {
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
    }

    .room-detail-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 800;
    }

    .room-detail-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .room-members-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .room-member-card {
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
        text-align: center;
    }

    .room-member-photo {
        width: 104px;
        height: 104px;
        border-radius: 26px;
        object-fit: cover;
        border: 4px solid rgba(37, 99, 235, 0.1);
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        margin-bottom: 16px;
    }

    .room-member-name {
        margin: 0 0 6px;
        font-size: 1.1rem;
    }

    .room-member-meta {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .room-member-actions {
        margin-top: 18px;
    }

    .room-empty {
        padding: 28px;
        text-align: center;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px dashed rgba(148, 163, 184, 0.4);
        color: #64748b;
    }
</style>

<div class="room-detail-page">
    <section class="room-detail-header">
        <div>
            <h2>Room {{ $roomNumber }}</h2>
            <p>Photo and name view for every user assigned to this room.</p>
        </div>

        <div class="room-detail-actions">
            <span class="room-detail-chip">
                <i class="fas fa-users"></i>
                {{ $users->count() }} User{{ $users->count() > 1 ? 's' : '' }}
            </span>
            <a href="{{ route('admin.users.by-room') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Back to Rooms
            </a>
        </div>
    </section>

    @if ($users->isEmpty())
        <div class="room-empty">
            No users found in room {{ $roomNumber }}.
        </div>
    @else
        <section class="room-members-grid">
            @foreach ($users as $user)
                <article class="room-member-card">
                    <img
                        class="room-member-photo"
                        src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('default-avatar.png') }}"
                        alt="{{ $user->full_name }}"
                    >
                    <h3 class="room-member-name">{{ $user->full_name }}</h3>
                    <p class="room-member-meta">Room Number: {{ $user->room_number }}</p>
                    @if ($user->department)
                        <p class="room-member-meta">{{ $user->department }}</p>
                    @endif

                    <div class="room-member-actions">
                        <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
