@foreach ($users as $user)
    <article class="student-card">
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
