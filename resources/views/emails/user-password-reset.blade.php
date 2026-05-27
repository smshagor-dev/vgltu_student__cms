<p>Hello {{ $user->full_name }},</p>
<p>Your account password has been reset by the admin.</p>
<p><strong>New Password:</strong> {{ $plainPassword }}</p>
<p>You can log in here: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
<p>Please change your password after logging in.</p>
