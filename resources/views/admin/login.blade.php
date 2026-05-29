<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | VGLTU</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f6fbff;
            --text: #163046;
            --muted: #607589;
            --line: rgba(117, 154, 181, 0.26);
            --card: rgba(255, 255, 255, 0.78);
            --primary: #2c6fb2;
            --primary-deep: #1f588f;
            --accent: #58a59a;
            --danger-bg: rgba(254, 242, 242, 0.92);
            --danger-text: #b91c1c;
            --shadow: 0 30px 80px rgba(58, 95, 128, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 14% 18%, rgba(107, 164, 214, 0.28), transparent 20%),
                radial-gradient(circle at 82% 16%, rgba(141, 209, 191, 0.24), transparent 18%),
                radial-gradient(circle at 22% 78%, rgba(244, 190, 158, 0.24), transparent 20%),
                radial-gradient(circle at 76% 82%, rgba(168, 188, 233, 0.26), transparent 18%),
                linear-gradient(180deg, #fbfdff 0%, var(--bg) 100%);
            position: relative;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
        }

        body::before {
            background:
                radial-gradient(circle at 12% 30%, rgba(81, 145, 201, 0.16), transparent 16%),
                radial-gradient(circle at 88% 32%, rgba(109, 185, 173, 0.14), transparent 15%),
                radial-gradient(circle at 52% 88%, rgba(232, 170, 141, 0.12), transparent 18%);
            filter: blur(28px);
        }

        body::after {
            background:
                linear-gradient(115deg, rgba(255, 255, 255, 0.24), transparent 36%),
                repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.06) 0 2px, transparent 2px 12px);
            opacity: 0.55;
        }

        .admin-login-card {
            position: relative;
            overflow: hidden;
            width: min(440px, 100%);
            padding: 34px 30px 28px;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.82);
            background: var(--card);
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px) saturate(1.1);
        }

        .admin-login-card::before,
        .admin-login-card::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
            filter: blur(2px);
        }

        .admin-login-card::before {
            width: 180px;
            height: 180px;
            top: -70px;
            right: -40px;
            background: radial-gradient(circle, rgba(112, 175, 224, 0.22), rgba(112, 175, 224, 0));
        }

        .admin-login-card::after {
            width: 170px;
            height: 170px;
            bottom: -80px;
            left: -50px;
            background: radial-gradient(circle, rgba(110, 190, 177, 0.18), rgba(110, 190, 177, 0));
        }

        .admin-login-header {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: 24px;
        }

        .admin-login-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(220, 237, 250, 0.86);
            color: var(--primary);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        h1 {
            margin: 18px 0 10px;
            font-size: 1.9rem;
            line-height: 1.1;
        }

        .admin-login-lead {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.7;
        }

        .admin-login-alert {
            position: relative;
            z-index: 1;
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid rgba(185, 28, 28, 0.12);
        }

        .admin-login-alert p {
            margin: 0;
            line-height: 1.55;
        }

        .admin-login-alert p + p {
            margin-top: 6px;
        }

        .admin-login-form {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 18px;
        }

        .admin-login-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #1e293b;
        }

        .admin-login-input-wrap {
            position: relative;
        }

        .admin-login-input-wrap i {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .admin-login-input-wrap--password .admin-login-control {
            padding-right: 52px;
        }

        .admin-login-password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 12px;
            background: rgba(219, 234, 254, 0.9);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .admin-login-password-toggle:hover,
        .admin-login-password-toggle:focus {
            background: rgba(191, 219, 254, 0.95);
            color: var(--primary-deep);
            outline: none;
        }

        .admin-login-control {
            width: 100%;
            min-height: 56px;
            padding: 15px 16px 15px 46px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(248, 251, 255, 0.82);
            color: var(--text);
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .admin-login-control:focus {
            outline: none;
            border-color: rgba(29, 78, 216, 0.55);
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
            background: #fff;
        }

        .admin-login-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .admin-login-check {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .admin-login-check input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        .admin-login-submit {
            min-height: 56px;
            border: 0;
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.14), transparent),
                linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            box-shadow: 0 18px 34px rgba(65, 126, 171, 0.26);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            cursor: pointer;
        }

        .admin-login-submit:hover,
        .admin-login-submit:focus {
            transform: translateY(-1px);
            box-shadow: 0 22px 38px rgba(29, 78, 216, 0.28);
            filter: saturate(1.05);
        }

        .admin-login-footer {
            position: relative;
            z-index: 1;
            margin-top: 18px;
            text-align: center;
        }

        .admin-login-footer a {
            color: var(--primary);
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-login-footer a:hover {
            color: var(--primary-deep);
        }

        @media (max-width: 575.98px) {
            body {
                padding: 14px;
            }

            .admin-login-card {
                padding: 24px 18px 22px;
                border-radius: 22px;
            }

            .admin-login-row {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-card">
        <div class="admin-login-header">
            <span class="admin-login-badge"><i class="fas fa-lock"></i> Admin Access</span>
            <h1>Login to dashboard</h1>
            <p class="admin-login-lead">Enter your administrator email and password to continue.</p>
        </div>

        @if ($errors->any())
            <div class="admin-login-alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-login-form">
            @csrf

            <div class="admin-login-field">
                <label for="admin_email">Email Address</label>
                <div class="admin-login-input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input
                        id="admin_email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="admin-login-control"
                        placeholder="admin@example.com"
                    >
                </div>
            </div>

            <div class="admin-login-field">
                <label for="admin_password">Password</label>
                <div class="admin-login-input-wrap admin-login-input-wrap--password">
                    <i class="fas fa-key"></i>
                    <input
                        id="admin_password"
                        type="password"
                        name="password"
                        required
                        class="admin-login-control"
                        placeholder="Enter your password"
                    >
                    <button type="button" class="admin-login-password-toggle" id="adminPasswordToggle" aria-label="Show password" aria-pressed="false">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="admin-login-row">
                <label class="admin-login-check" for="remember_admin">
                    <input id="remember_admin" type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="admin-login-submit">Login</button>
        </form>

        <div class="admin-login-footer">
            <a href="{{ route('welcome') }}">Go to Frontend</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('admin_password');
        const passwordToggle = document.getElementById('adminPasswordToggle');

        if (passwordInput && passwordToggle) {
            passwordToggle.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordToggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                passwordToggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                passwordToggle.innerHTML = isPassword
                    ? '<i class="fas fa-eye-slash"></i>'
                    : '<i class="fas fa-eye"></i>';
            });
        }
    </script>
</body>
</html>
