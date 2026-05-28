<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="margin:0;padding:24px;background:#f6efe8;font-family:Arial,sans-serif;color:#241726;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid rgba(36,23,38,0.08);">
        <div style="padding:32px;background:linear-gradient(135deg,#241726,#bb3e71);color:#ffffff;">
            <div style="font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;opacity:0.88;">VGLTU Student Forum</div>
            <h1 style="margin:14px 0 0;font-size:28px;line-height:1.2;">Reset your password</h1>
        </div>

        <div style="padding:32px;">
            <p style="margin:0 0 16px;">Hello {{ $user->full_name ?: 'Student' }},</p>
            <p style="margin:0 0 16px;line-height:1.7;">
                We received a request to reset your VGLTU Student Forum password. Click the button below to choose a new password.
            </p>

            <div style="margin:28px 0;">
                <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 24px;border-radius:999px;background:#bb3e71;color:#ffffff;text-decoration:none;font-weight:700;">
                    Reset Password
                </a>
            </div>

            <p style="margin:0 0 12px;line-height:1.7;">
                This reset link will expire in {{ $expireMinutes }} minutes.
            </p>
            <p style="margin:0 0 12px;line-height:1.7;">
                If you did not request a password reset, you can safely ignore this email.
            </p>
            <p style="margin:18px 0 0;line-height:1.7;">
                If the button does not work, use this link:<br>
                <a href="{{ $resetUrl }}" style="color:#bb3e71;word-break:break-all;">{{ $resetUrl }}</a>
            </p>
        </div>
    </div>
</body>
</html>
