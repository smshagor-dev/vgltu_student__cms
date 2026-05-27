<div style="font-family: Arial, sans-serif; color: #241726; line-height: 1.7;">
    <h2>Congratulations!</h2>
    <p>Hello {{ $user->full_name }},</p>
    <p>Your account has been approved successfully. You can now login and use the portal.</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $plainPassword ?: 'Use the password you created during registration.' }}</p>
    <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
    <p>Congratulations again. You are now able to login.</p>
</div>
