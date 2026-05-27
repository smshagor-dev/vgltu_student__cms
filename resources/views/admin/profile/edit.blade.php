@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Edit Admin Profile</h2>
        <p>Manage account identity, profile image, and password updates from one polished control panel.</p>
    </section>

    <section class="admin-form-shell">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-grid-2">
            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__title">
                        <h3>Profile Overview</h3>
                        <p>Current photo and account identity.</p>
                    </div>
                </div>

                <div class="text-center mb-4">
                    @if ($admin->photo)
                        <img class="admin-media" src="{{ asset('storage/' . $admin->photo) }}" alt="Admin Photo">
                    @else
                        <div class="admin-empty">No profile image uploaded</div>
                    @endif
                </div>

                <div class="admin-kv">
                    <div class="admin-kv-item"><span>Name</span><strong>{{ $admin->name }}</strong></div>
                    <div class="admin-kv-item"><span>Email</span><strong>{{ $admin->email }}</strong></div>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__title">
                        <h3>Update Details</h3>
                        <p>Apply account and password changes safely.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="photo" class="form-label">Upload New Profile Photo</label>
                        <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror">
                        <small class="form-text text-muted">Allowed file types: JPG, PNG (Max: 2MB)</small>
                        @error('photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" placeholder="Enter your name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" placeholder="Enter your email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="admin-actions-inline">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
