@extends('layouts.app')

@section('content')
<style>
    .login-page {
        width: min(1200px, calc(100% - 32px));
        margin: 36px auto 52px;
    }

    .login-shell {
        display: grid;
        grid-template-columns: minmax(320px, 0.95fr) minmax(320px, 1.05fr);
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(76, 42, 65, 0.12);
    }

    .login-showcase {
        position: relative;
        padding: 42px 34px;
        background:
            linear-gradient(145deg, rgba(187, 62, 113, 0.92), rgba(36, 23, 38, 0.92)),
            url('{{ asset('28020.png') }}') center/cover no-repeat;
        color: #fff;
        min-height: 100%;
    }

    .login-showcase::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0));
        pointer-events: none;
    }

    .login-showcase__content {
        position: relative;
        z-index: 1;
    }

    .login-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .login-showcase h1 {
        margin: 18px 0 14px;
        font-size: clamp(2rem, 4vw, 3.4rem);
        line-height: 1.08;
        font-weight: 800;
        text-transform: none;
        letter-spacing: 0;
    }

    .login-showcase p {
        margin: 0;
        max-width: 480px;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.85;
        font-size: 15px;
    }

    .login-showcase__stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 30px;
    }

    .login-stat {
        padding: 16px 18px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(8px);
    }

    .login-stat strong {
        display: block;
        font-size: 1.55rem;
        font-weight: 800;
    }

    .login-stat span {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.5;
    }

    .login-form-panel {
        padding: 40px 36px;
        background: linear-gradient(180deg, #fffdfa 0%, #fff7f1 100%);
    }

    .login-form-head {
        margin-bottom: 24px;
    }

    .login-form-head__kicker {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.12);
        color: #bb3e71;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .login-form-head h2 {
        margin: 14px 0 10px;
        color: #241726;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .login-form-head p {
        margin: 0;
        color: #6f6572;
        line-height: 1.75;
    }

    .login-field {
        margin-bottom: 18px;
    }

    .login-field label {
        display: block;
        margin-bottom: 8px;
        color: #241726;
        font-weight: 700;
    }

    .login-field .form-control {
        min-height: 52px;
        border-radius: 16px;
        border: 1px solid rgba(35, 23, 38, 0.12);
        padding: 0.85rem 1rem;
        box-shadow: none;
    }

    .login-field .form-control:focus {
        border-color: rgba(187, 62, 113, 0.5);
        box-shadow: 0 0 0 0.18rem rgba(215, 89, 139, 0.14);
    }

    .login-password-group {
        display: grid;
        grid-template-columns: 1fr 56px;
    }

    .login-password-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .login-password-toggle {
        border: 1px solid rgba(35, 23, 38, 0.12);
        border-left: 0;
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
        background: #fff;
        color: #6f6572;
    }

    .login-password-toggle:hover {
        color: #bb3e71;
    }

    .login-remember {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .login-remember .form-check {
        margin: 0;
    }

    .login-remember a,
    .login-extra a {
        color: #bb3e71;
        text-decoration: none;
        font-weight: 700;
    }

    .login-remember a:hover,
    .login-extra a:hover {
        color: #241726;
    }

    .login-submit {
        width: 100%;
        min-height: 54px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(135deg, #f173aa, #bb3e71);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 16px 30px rgba(215, 89, 139, 0.26);
    }

    .login-submit:hover {
        color: #fff;
    }

    .login-extra {
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(35, 23, 38, 0.08);
        text-align: center;
        color: #6f6572;
        line-height: 1.7;
    }

    .login-register-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 12px;
        padding: 11px 20px;
        border-radius: 999px;
        background: #241726;
        color: #fff !important;
        font-weight: 700;
        text-decoration: none;
    }

    .login-register-btn:hover {
        background: #bb3e71;
    }

    @media (max-width: 991px) {
        .login-shell {
            grid-template-columns: 1fr;
        }

        .login-showcase,
        .login-form-panel {
            padding: 32px 24px;
        }
    }

    @media (max-width: 767px) {
        .login-page {
            width: calc(100% - 16px);
            margin: 20px auto 34px;
        }

        .login-shell {
            border-radius: 22px;
        }

        .login-showcase,
        .login-form-panel {
            padding: 24px 18px;
        }

        .login-showcase__stats {
            grid-template-columns: 1fr;
        }

        .login-remember {
            align-items: flex-start;
        }

        .login-showcase {
            display: none;
        }

        .login-shell {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="login-page">
    <div class="login-shell">
        <div class="login-showcase">
            <div class="login-showcase__content">
                <span class="login-pill">Student Access</span>
                <h1>Welcome back to the VGLTU community.</h1>
                <p>Sign in to manage your profile, access student services, and stay connected with the university platform in a cleaner and more professional workspace.</p>

                <div class="login-showcase__stats">
                    <div class="login-stat">
                        <strong>24/7</strong>
                        <span>Access your dashboard and profile updates anytime.</span>
                    </div>
                    <div class="login-stat">
                        <strong>Secure</strong>
                        <span>Protected login for approved students and registered users.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-head">
                <span class="login-form-head__kicker">Login Portal</span>
                <h2>{{ __('Login to Your Account') }}</h2>
                <p>Use your registered email and password to continue to your account.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login-field">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="login-field">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="login-password-group">
                        <input
                            id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" id="togglePassword" class="login-password-toggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="login-remember">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                </div>

                <button type="submit" class="login-submit">{{ __('Login') }}</button>

                <div class="login-extra">
                    <div>{{ __("Don't have an account?") }}</div>
                    <a href="{{ route('register') }}" class="login-register-btn">{{ __('Registration Now') }}</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePasswordButton && passwordInput) {
            togglePasswordButton.addEventListener('click', function () {
                const icon = this.querySelector('i');
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            });
        }
    });
</script>
@endsection
