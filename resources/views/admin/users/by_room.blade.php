@extends('layouts.admin_app')

@section('content')
<style>
    .room-page {
        display: grid;
        gap: 24px;
    }

    .room-hero {
        padding: 28px;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(59, 130, 246, 0.22), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1e3a8a 52%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
    }

    .room-hero h2 {
        color: #fff;
        margin-bottom: 8px;
    }

    .room-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        max-width: 760px;
    }

    .room-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 22px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
    }

    .room-toolbar__count {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 800;
    }

    .room-search {
        max-width: 360px;
        width: 100%;
    }

    .room-search input {
        min-height: 48px;
        border-radius: 14px;
    }

    .room-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }

    .room-card {
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .room-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 40px rgba(15, 23, 42, 0.12);
    }

    .room-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
    }

    .room-card__icon {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        font-size: 1.1rem;
    }

    .room-card__room {
        margin: 0 0 6px;
        font-size: 1.35rem;
    }

    .room-card__text {
        color: #64748b;
        margin: 0;
    }

    .room-card__count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 800;
        font-size: 0.9rem;
    }

    .room-card__actions {
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

<div class="room-page">
    <section class="room-hero">
        <h2>User by Room</h2>
        <p>Each room number shows how many users are assigned there. Use the view button to open the room and see student photos and names.</p>
    </section>

    <section class="room-toolbar">
        <div class="room-toolbar__count">
            <i class="fas fa-door-open"></i>
            <span>Total Rooms: {{ $rooms->count() }}</span>
        </div>

        <div class="room-search">
            <input type="text" id="roomSearch" class="form-control" placeholder="Search by room number">
        </div>
    </section>

    @if ($rooms->isEmpty())
        <div class="room-empty">
            No room data found yet.
        </div>
    @else
        <section class="room-grid" id="roomGrid">
            @foreach ($rooms as $room)
                <article class="room-card" data-room="{{ strtolower($room->room_number) }}">
                    <div class="room-card__top">
                        <div>
                            <div class="room-card__icon">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                        <span class="room-card__count">
                            <i class="fas fa-users"></i>
                            {{ $room->total_users }} User{{ $room->total_users > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <h3 class="room-card__room">Room {{ $room->room_number }}</h3>
                    <p class="room-card__text">See everyone assigned to this room in one clean profile view.</p>

                    <div class="room-card__actions">
                        <a href="{{ route('admin.users.by-room.show', $room->room_number) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-4 d-flex justify-content-center">
            {{ $rooms->links() }}
        </div>
    @endif
</div>

<script>
    const roomSearch = document.getElementById('roomSearch');
    const roomCards = document.querySelectorAll('#roomGrid .room-card');

    if (roomSearch) {
        roomSearch.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();

            roomCards.forEach(function (card) {
                const room = card.getAttribute('data-room') || '';
                card.style.display = room.includes(term) ? '' : 'none';
            });
        });
    }
</script>
@endsection
