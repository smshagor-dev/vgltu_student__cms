<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin 2FA Verification | VGLTU</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(15, 118, 110, 0.14), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            color: #163046;
        }

        .card {
            width: min(460px, 100%);
            padding: 32px 28px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.75);
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        h1 {
            margin: 18px 0 10px;
            text-align: center;
            font-size: 1.9rem;
        }

        p.lead {
            margin: 0 0 24px;
            text-align: center;
            color: #607589;
            line-height: 1.7;
        }

        .alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #fef2f2;
            color: #b91c1c;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            min-height: 56px;
            padding: 15px 16px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 18px;
            background: rgba(248, 251, 255, 0.82);
            font-size: 1rem;
        }

        .field input:focus {
            outline: none;
            border-color: rgba(29, 78, 216, 0.55);
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
            background: #fff;
        }

        .hint {
            margin-top: 8px;
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .actions {
            display: grid;
            gap: 12px;
        }

        .btn-primary,
        .btn-secondary {
            min-height: 54px;
            border-radius: 18px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-primary {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, #2c6fb2, #58a59a);
            box-shadow: 0 18px 34px rgba(65, 126, 171, 0.26);
        }

        .btn-secondary {
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: #fff;
            color: #163046;
        }
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align:center;">
            <span class="badge"><i class="fas fa-mobile-alt"></i> Google Authenticator</span>
        </div>
        <h1>Verify your admin login</h1>
        <p class="lead">Enter the 6-digit code from your authenticator app. You can also use a recovery code if needed.</p>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.two-factor.verify') }}">
            @csrf
            <div class="field">
                <label for="code">Authenticator or Recovery Code</label>
                <input id="code" type="text" name="code" value="{{ old('code') }}" placeholder="123456 or ABCD-EFGH" required autofocus>
                <div class="hint">Admin: {{ $admin->email }}</div>
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">Verify and Continue</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.two-factor.cancel') }}" style="margin-top:12px;">
            @csrf
            <button type="submit" class="btn-secondary" style="width:100%;">Cancel Sign In</button>
        </form>
    </div>
</body>
</html>
