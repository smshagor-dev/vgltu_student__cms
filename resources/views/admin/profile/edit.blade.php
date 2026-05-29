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

        @if ($errors->has('two_factor'))
            <div class="alert alert-danger">{{ $errors->first('two_factor') }}</div>
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

        <div class="admin-grid-2 mt-4" id="two-factor">
            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__title">
                        <h3>Google Authenticator 2FA</h3>
                        <p>Protect admin access with time-based one-time codes.</p>
                    </div>
                </div>

                @if ($admin->hasTwoFactorEnabled())
                    <div class="alert alert-success">Two-factor authentication is enabled for this admin account.</div>
                    <div class="admin-kv mb-4">
                        <div class="admin-kv-item"><span>Status</span><strong>Enabled</strong></div>
                        <div class="admin-kv-item"><span>Activated</span><strong>{{ optional($admin->two_factor_confirmed_at)->format('d M Y, h:i A') }}</strong></div>
                    </div>

                    <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="disable_current_password" class="form-label">Current Password</label>
                            <input type="password" id="disable_current_password" name="current_password" class="form-control @error('disable_current_password') is-invalid @enderror" placeholder="Enter current password" required>
                            @error('disable_current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-outline-danger">Disable 2FA</button>
                    </form>

                    <form action="{{ route('admin.profile.two-factor.recovery-codes') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recovery_current_password" class="form-label">Current Password</label>
                            <input type="password" id="recovery_current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                            @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Regenerate Recovery Codes</button>
                    </form>
                @else
                    <div class="alert alert-warning">Two-factor authentication is not enabled yet. Scan the QR code and verify one code to turn it on.</div>
                    <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST">
                        @csrf
                        <div class="mb-3 text-center">
                            @if ($setupQrCodeUrl)
                                <img src="{{ $setupQrCodeUrl }}" alt="2FA QR Code" style="width: min(240px, 100%); background: #fff; padding: 10px; border-radius: 18px;">
                            @endif
                        </div>
                        @if ($setupSecret)
                            <div class="mb-3">
                                <label class="form-label">Manual Setup Key</label>
                                <div class="form-control" style="min-height:auto; font-weight:700; letter-spacing:0.08em; word-break: break-all;">{{ $setupSecret }}</div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="two_factor_code" class="form-label">Authenticator Code</label>
                            <input type="text" id="two_factor_code" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="Enter 6-digit code" required>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enable Google Authenticator</button>
                    </form>
                @endif
            </div>

            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__title">
                        <h3>Recovery Codes</h3>
                        <p>Store these in a safe place. Each code can be used once if you lose your authenticator device.</p>
                    </div>
                </div>

                @if (!empty($plainRecoveryCodes))
                    <div class="alert alert-info">These recovery codes are shown only now. Save them before leaving this page.</div>
                    <div class="row g-3">
                        @foreach ($plainRecoveryCodes as $recoveryCode)
                            <div class="col-md-6">
                                <div class="form-control" style="min-height:auto; font-weight:700; text-align:center; letter-spacing:0.08em;">{{ $recoveryCode }}</div>
                            </div>
                        @endforeach
                    </div>
                @elseif ($admin->hasTwoFactorEnabled())
                    <div class="admin-empty">Recovery codes are hidden for security. Use the regenerate button to create a new set.</div>
                @else
                    <div class="admin-empty">Recovery codes will appear here after you enable Google Authenticator 2FA.</div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
