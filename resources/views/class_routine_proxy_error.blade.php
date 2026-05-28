<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Routine Unavailable</title>
    <style>
        html, body {
            margin: 0;
            min-height: 100%;
            background: #ffffff;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .proxy-error {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .proxy-error__card {
            max-width: 720px;
            width: 100%;
            padding: 28px;
            border-radius: 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.1);
        }

        .proxy-error__card h1 {
            margin: 0 0 10px;
        }

        .proxy-error__card p {
            margin: 0;
            line-height: 1.65;
            color: #5b6474;
        }

        .proxy-error__actions {
            margin-top: 18px;
        }

        .proxy-error__actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 18px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            background: #241726;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="proxy-error">
        <div class="proxy-error__card">
            <h1>Class routine is temporarily unavailable</h1>
            <p>
                The content-only routine view could not be loaded right now. Upstream response status:
                <strong>{{ $statusCode }}</strong>.
            </p>
            <div class="proxy-error__actions">
                <a href="{{ $fallbackUrl }}" target="_blank" rel="noopener noreferrer">Open Original Schedule</a>
            </div>
        </div>
    </div>
</body>
</html>
