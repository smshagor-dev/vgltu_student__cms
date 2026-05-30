<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete your student document data</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <div style="max-width:640px; margin:0 auto; padding:24px 16px;">
        <div style="background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 14px 40px rgba(30, 60, 114, 0.12);">
            <div style="padding:24px 28px; background:linear-gradient(135deg, #1e3c72, #2a5298); color:#ffffff;">
                <h1 style="margin:0; font-size:28px; line-height:1.25;">Complete your student document data</h1>
            </div>

            <div style="padding:28px;">
                <p style="margin-top:0; margin-bottom:18px; font-size:16px; line-height:1.7;">
                    Hello {{ $user->full_name ?: 'Student' }},
                </p>

                <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#374151;">
                    Your passport, visa, and green card information is currently missing in the portal.
                </p>

                <p style="margin:0; font-size:15px; line-height:1.8; color:#374151;">
                    Please add or update your student document data as soon as possible to keep your profile complete.
                </p>

                <div style="margin-top:28px;">
                    <a href="{{ $actionUrl }}" style="display:inline-block; padding:12px 20px; border-radius:999px; background:#1e3c72; color:#ffffff; text-decoration:none; font-weight:700;">
                        Add Student Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
