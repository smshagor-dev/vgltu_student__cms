@extends('layouts.admin_app')

@section('content')
<style>
    .student-directory {
        display: grid;
        gap: 24px;
    }

    .student-search {
        max-width: 420px;
    }

    .student-card-grid {
        display: grid;
        gap: 18px;
    }

    .student-card {
        display: grid;
        grid-template-columns: 132px minmax(0, 1fr);
        gap: 22px;
        padding: 22px;
        border-radius: 26px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .student-card__photo {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .student-card__photo img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 28px;
        border: 4px solid rgba(37, 99, 235, 0.08);
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
    }

    .student-card__meta {
        display: grid;
        gap: 12px;
    }

    .student-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .student-card__top h4 {
        margin: 0 0 6px;
    }

    .student-card__top p {
        margin: 0;
        color: #64748b;
    }

    .student-card__facts {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }

    .student-card__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .student-card__actions form {
        margin: 0;
    }

    @media (max-width: 767.98px) {
        .student-card {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .student-card__actions {
            justify-content: center;
        }
    }
</style>

<div class="admin-page student-directory">
    <section class="admin-hero-card">
        <h2>Student Directory</h2>
        <p>Browse filtered student records with a cleaner operational view for profile review, password reset, and account actions.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Students Details</h3>
                <p>Search the current page by student name or room number.</p>
            </div>
            <div class="admin-chip">
                <i class="fas fa-users"></i>
                <span>{{ method_exists($users, 'total') ? $users->total() : $users->count() }} Students</span>
            </div>
        </div>

        <div class="student-search mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or room number">
        </div>

        @if ($users->isEmpty())
            <div class="admin-empty">No students found for this filter.</div>
        @else
            <div class="student-card-grid">
                @foreach ($users as $user)
                    <article class="student-card" data-name="{{ strtolower($user->full_name) }}" data-room="{{ strtolower($user->room_number) }}">
                        <div class="student-card__photo">
                            @if($user->photo)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#photoModal{{ $user->id }}">
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->full_name }}">
                                </a>
                            @else
                                <img src="{{ asset('storage/default.png') }}" alt="Default Photo">
                            @endif
                        </div>

                        <div class="student-card__meta">
                            <div class="student-card__top">
                                <div>
                                    <h4>{{ $user->full_name }}</h4>
                                    <p>Room {{ $user->room_number ?: 'N/A' }}</p>
                                </div>
                                <span class="admin-chip">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $user->mobile_number ?: 'No number' }}</span>
                                </span>
                            </div>

                            <div class="student-card__facts">
                                <div class="admin-kv-item">
                                    <span>Email</span>
                                    <strong>{{ $user->email ?: 'N/A' }}</strong>
                                </div>
                                <div class="admin-kv-item">
                                    <span>Country</span>
                                    <strong>{{ $user->country ?: 'N/A' }}</strong>
                                </div>
                                <div class="admin-kv-item">
                                    <span>Department</span>
                                    <strong>{{ $user->department ?: 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="student-card__actions">
                                <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete User</button>
                                </form>
                                <form action="{{ route('admin.forgetPassword', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset this user\'s password?');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </article>

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
            </div>
        @endif
    </section>

    <div class="admin-pagination">
        {{ $users->links() }}
    </div>
</div>

<script>
function filterUsers() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let userCards = document.getElementsByClassName('student-card');

    for (let i = 0; i < userCards.length; i++) {
        let name = userCards[i].getAttribute('data-name');
        let room = userCards[i].getAttribute('data-room');

        userCards[i].style.display = (name.includes(input) || room.includes(input)) ? 'grid' : 'none';
    }
}

document.getElementById('searchInput').addEventListener('input', filterUsers);
</script>
@endsection
