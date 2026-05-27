@extends('layouts.admin_app')

@section('content')
<style>
    .pending-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    .pending-card {
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .pending-card__photo {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        object-fit: cover;
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
    }
</style>

<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Pending User Registrations</h2>
        <p>Review queued student accounts with a cleaner approval workflow, better visibility, and faster action controls.</p>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Approval Queue</h3>
                <p>Approve, reject, or inspect pending registrations.</p>
            </div>
            <span class="admin-chip">
                <i class="fas fa-user-clock"></i>
                <span>{{ method_exists($pendingUsers, 'total') ? $pendingUsers->total() : $pendingUsers->count() }} Pending</span>
            </span>
        </div>

        <div class="admin-data-card mb-4">
            <div class="admin-table-wrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Room Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                            <tr>
                                <td>
                                    @if($user->photo)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#photoModal{{ $user->id }}">
                                            <img class="admin-avatar" src="{{ asset('storage/' . $user->photo) }}" alt="User Photo">
                                        </a>
                                    @else
                                        <img class="admin-avatar" src="{{ asset('storage/default.png') }}" alt="Default Photo">
                                    @endif
                                </td>
                                <td>{{ $user->room_number }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="admin-actions-inline">
                                        <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-outline-primary">View</a>
                                        <form action="{{ route('admin.approveUser', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.rejectUser', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="photoModal{{ $user->id }}" tabindex="-1" aria-labelledby="photoModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="photoModalLabel{{ $user->id }}">{{ $user->full_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/default.png') }}" class="img-fluid rounded-4" alt="Full Photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pending-grid">
            @foreach($pendingUsers as $user)
                <article class="pending-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="pending-card__photo">
                        @else
                            <img src="{{ asset('storage/default.png') }}" alt="Default Photo" class="pending-card__photo">
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $user->full_name }}</h4>
                            <p class="mb-0 text-muted">Room {{ $user->room_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="admin-kv mb-3">
                        <div class="admin-kv-item"><span>Email</span><strong>{{ $user->email }}</strong></div>
                    </div>

                    <div class="admin-actions-inline">
                        <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-outline-primary">View</a>
                        <form action="{{ route('admin.approveUser', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Approve</button>
                        </form>
                        <form action="{{ route('admin.rejectUser', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <div class="admin-pagination">
        {{ $pendingUsers->links() }}
    </div>
</div>
@endsection
