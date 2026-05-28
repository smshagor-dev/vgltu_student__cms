<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0; padding:0; background:#eef4fb; font-family:Arial, Helvetica, sans-serif; color:#142132;">
    <div style="max-width:720px; margin:0 auto; padding:24px 16px;">
        <div style="background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 14px 40px rgba(15, 23, 42, 0.12);">
            <div style="padding:26px 30px; background:linear-gradient(135deg, #0f172a, #1d4ed8); color:#ffffff;">
                <div style="font-size:13px; letter-spacing:0.12em; text-transform:uppercase; opacity:0.75;">VGLTU Admin Notice</div>
                <h1 style="margin:10px 0 0; font-size:28px; line-height:1.3;">{{ $title }}</h1>
            </div>
            <div style="padding:28px 30px;">
                <p style="margin:0 0 18px; font-size:15px; line-height:1.7;">Hello {{ $user->full_name ?: 'Student' }},</p>

                <div style="font-size:15px; line-height:1.8; color:#334155;">
                    {!! $bodyHtml !!}
                </div>

                @if ($actionUrl)
                    <div style="margin-top:28px;">
                        <a href="{{ $actionUrl }}" style="display:inline-block; padding:12px 22px; border-radius:999px; background:#1d4ed8; color:#ffffff; text-decoration:none; font-weight:700;">
                            Open Portal
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
