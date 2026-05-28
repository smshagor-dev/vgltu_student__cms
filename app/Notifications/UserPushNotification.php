<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Support\WebPushService;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class UserPushNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $type = 'general',
        private readonly ?string $url = null,
        private readonly ?string $icon = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (WebPushService::isConfigured()) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $targetUrl = $this->url ?: route('home');
        $iconUrl = asset('logo_en.png');
        $badgeUrl = asset('pwa-icon-192.png');

        return (new WebPushMessage)
            ->title($this->title)
            ->icon($iconUrl)
            ->badge($badgeUrl)
            ->body($this->message)
            ->action('Open', $targetUrl)
            ->data([
                'url' => $targetUrl,
                'icon' => $iconUrl,
                'badge' => $badgeUrl,
                'type' => $this->type,
                'tag' => 'user-notification-' . md5($this->type . '-' . $this->title),
            ]);
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage($this->toArray($notifiable));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url ?: route('home'),
            'icon' => $this->icon ?: 'fas fa-bell',
        ];
    }
}
