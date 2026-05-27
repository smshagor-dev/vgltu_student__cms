<div style="font-family: Arial, sans-serif; color: #241726; line-height: 1.7;">
    <h2>Registration Successful</h2>
    <p>Hello {{ $user->full_name }},</p>
    <p>Your registration was successful and is now pending admin approval.</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $plainPassword }}</p>
    <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
    <p>We will notify you again once the admin approves your account.</p>
</div>
