@extends('layouts.admin_app')

@section('content')
<style>
    .audit-page {
        display: grid;
        gap: 24px;
    }

    .audit-grid {
        display: grid;
        gap: 18px;
    }

    .audit-card {
        display: grid;
        gap: 14px;
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .audit-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .audit-card__top h4 {
        margin: 0 0 6px;
    }

    .audit-card__top p {
        margin: 0;
        color: #64748b;
    }

    .audit-meta {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .audit-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .audit-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .audit-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .audit-status.is-approved {
        background: #dcfce7;
        color: #166534;
    }

    .audit-status.is-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .audit-group-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .audit-members {
        display: grid;
        gap: 14px;
    }

    .audit-member {
        display: grid;
        grid-template-columns: 72px minmax(0, 1fr);
        gap: 14px;
        padding: 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .audit-member__photo img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .audit-member__body {
        display: grid;
        gap: 10px;
    }

    @media (max-width: 767.98px) {
        .audit-member {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page audit-page">
    <section class="admin-hero-card">
        <h2>{{ $title }}</h2>
        <p>{{ $description }}</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>User List</h3>
                <p>Review matching users and jump straight into profile details.</p>
            </div>
            <div class="admin-chip">
                <i class="fas fa-users"></i>
                <span>
                    {{ $mode === 'duplicate-groups'
                        ? (method_exists($groups, 'total') ? $groups->total() : $groups->count()) . ' Groups'
                        : (method_exists($users, 'total') ? $users->total() : $users->count()) . ' Users' }}
                </span>
            </div>
        </div>

        @if (($mode === 'duplicate-groups' && $groups->isEmpty()) || ($mode !== 'duplicate-groups' && $users->isEmpty()))
            <div class="admin-empty">{{ $emptyMessage }}</div>
        @elseif ($mode === 'duplicate-groups')
            <div class="audit-grid">
                @foreach ($groups as $group)
                    <article class="audit-card">
                        <div class="audit-group-head">
                            <div>
                                <h4>{{ $group->group_value }}</h4>
                                <p>{{ $group->count }} users matched in this duplicate group.</p>
                            </div>
                            <span class="audit-badge"><i class="fas fa-layer-group"></i> {{ $group->group_type }} Group</span>
                        </div>

                        <div class="audit-badges">
                            @foreach ($group->reasons as $reason)
                                <span class="audit-badge"><i class="fas fa-link"></i> Match by {{ $reason }}</span>
                            @endforeach
                        </div>

                        <div class="audit-members">
                            @foreach ($group->users as $user)
                                <article class="audit-member">
                                    <div class="audit-member__photo">
                                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/default.png') }}" alt="{{ $user->full_name }}">
                                    </div>

                                    <div class="audit-member__body">
                                        <div class="audit-card__top">
                                            <div>
                                                <h4>{{ $user->full_name }}</h4>
                                                <p>{{ $user->email ?: 'No email' }}</p>
                                            </div>
                                            <span class="audit-status {{ $user->approved ? 'is-approved' : 'is-pending' }}">
                                                <i class="fas {{ $user->approved ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                                {{ $user->approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </div>

                                        <div class="audit-meta">
                                            <div class="admin-kv-item">
                                                <span>Passport Number</span>
                                                <strong>{{ $user->studentsData->passport_number ?? 'No passport data' }}</strong>
                                            </div>
                                            <div class="admin-kv-item">
                                                <span>Room</span>
                                                <strong>{{ $user->room_number ?: 'N/A' }}</strong>
                                            </div>
                                            <div class="admin-kv-item">
                                                <span>Registered</span>
                                                <strong>{{ $user->created_at?->format('d M Y, h:i A') ?: 'N/A' }}</strong>
                                            </div>
                                        </div>

                                        <div class="admin-actions-inline">
                                            <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-primary">Open User</a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary">Edit</a>
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete User</button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="audit-grid">
                @foreach ($users as $user)
                    <article class="audit-card">
                        <div class="audit-card__top">
                            <div>
                                <h4>{{ $user->full_name }}</h4>
                                <p>{{ $user->email ?: 'No email' }}</p>
                            </div>
                            <span class="audit-status {{ $user->approved ? 'is-approved' : 'is-pending' }}">
                                <i class="fas {{ $user->approved ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                {{ $user->approved ? 'Approved' : 'Pending' }}
                            </span>
                        </div>

                        @if ($showDuplicateReasons && !empty($user->duplicate_match_reasons))
                            <div class="audit-badges">
                                @foreach ($user->duplicate_match_reasons as $reason)
                                    <span class="audit-badge"><i class="fas fa-link"></i> Match by {{ $reason }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="audit-meta">
                            <div class="admin-kv-item">
                                <span>Passport Number</span>
                                <strong>{{ $user->studentsData->passport_number ?? 'No passport data' }}</strong>
                            </div>
                            <div class="admin-kv-item">
                                <span>Room</span>
                                <strong>{{ $user->room_number ?: 'N/A' }}</strong>
                            </div>
                            <div class="admin-kv-item">
                                <span>Registered</span>
                                <strong>{{ $user->created_at?->format('d M Y, h:i A') ?: 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="admin-actions-inline">
                            <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-primary">Open User</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete User</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <div class="admin-pagination">
        {{ $mode === 'duplicate-groups' ? $groups->links() : $users->links() }}
    </div>
</div>
@endsection
