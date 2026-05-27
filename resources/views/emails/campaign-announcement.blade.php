<div style="font-family: Arial, sans-serif; color: #241726; line-height: 1.7;">
    <h2>{{ $notificationTitle }}</h2>
    <p>Hello {{ $user->full_name }},</p>
    <p>{{ $notificationDescription }}</p>
    <p><strong>Campaign:</strong> {{ $campaign->title }}</p>
    <p><strong>Join Campaign:</strong> <a href="{{ $campaignUrl }}">{{ $campaignUrl }}</a></p>
    <p>Please open the link and submit your response.</p>
</div>
