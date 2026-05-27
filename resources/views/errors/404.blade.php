<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f9f9f9;
        }
        h1 {
            font-size: 100px;
            color: #ff6347;
        }
        p {
            font-size: 24px;
        }
        a {
            color: #008CBA;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you're looking for cannot be found.</p>
    <p><a href="{{ url('/') }}">Go back to the homepage</a></p>
</body>
</html>
