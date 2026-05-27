<p>Dear {{ $user->full_name }},</p>

<p>Your visa expiry date was recorded as <strong>{{ $expiryDate->format('d M Y') }}</strong>, and it has now been more than 10 days without an update in the system.</p>

<p>Please update your student visa information as soon as possible to keep your record accurate and avoid further issues.</p>

<p>
    <a href="{{ $editUrl }}">Update visa information now</a>
</p>

<p>Thank you.</p>
