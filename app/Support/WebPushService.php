<?php

namespace App\Support;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Collection;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public static function isConfigured(): bool
    {
        return filled(config('services.webpush.subject'))
            && filled(config('services.webpush.public_key'))
            && filled(config('services.webpush.private_key'));
    }

    public static function publicKey(): ?string
    {
        return config('services.webpush.public_key');
    }

    public static function subscribe(User $user, array $subscription, ?string $userAgent = null): void
    {
        if (blank($subscription['endpoint'] ?? null) || blank($subscription['keys']['p256dh'] ?? null) || blank($subscription['keys']['auth'] ?? null)) {
            return;
        }

        PushSubscription::query()->updateOrCreate(
            ['endpoint' => $subscription['endpoint']],
            [
                'user_id' => $user->id,
                'public_key' => $subscription['keys']['p256dh'],
                'auth_token' => $subscription['keys']['auth'],
                'content_encoding' => $subscription['contentEncoding'] ?? 'aesgcm',
                'user_agent' => $userAgent,
            ]
        );

        $user->forceFill(['browser_notifications_enabled' => true])->save();
    }

    public static function unsubscribe(User $user, ?string $endpoint = null): void
    {
        $query = $user->pushSubscriptions();

        if ($endpoint) {
            $query->where('endpoint', $endpoint);
        }

        $query->delete();

        if (! $user->pushSubscriptions()->exists()) {
            $user->forceFill(['browser_notifications_enabled' => false])->save();
        }
    }

    public static function sendToUsers(iterable $userIds, array $payload): void
    {
        if (! self::isConfigured()) {
            return;
        }

        $userIds = collect($userIds)->filter()->unique()->values();
        if ($userIds->isEmpty()) {
            return;
        }

        $subscriptions = PushSubscription::query()
            ->whereIn('user_id', $userIds)
            ->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.webpush.subject'),
                'publicKey' => config('services.webpush.public_key'),
                'privateKey' => config('services.webpush.private_key'),
            ],
        ]);

        $message = json_encode([
            'title' => $payload['title'] ?? 'Notification',
            'body' => $payload['description'] ?? 'Open the portal to view this notification.',
            'url' => $payload['url'] ?? route('home'),
            'icon' => asset('default-avatar.png'),
            'badge' => asset('default-avatar.png'),
            'tag' => 'user-notification-' . md5(($payload['type'] ?? 'general') . '-' . ($payload['title'] ?? '')),
        ]);

        foreach ($subscriptions as $subscriptionModel) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscriptionModel->endpoint,
                    'publicKey' => $subscriptionModel->public_key,
                    'authToken' => $subscriptionModel->auth_token,
                    'contentEncoding' => $subscriptionModel->content_encoding ?: 'aesgcm',
                ]),
                $message
            );
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                continue;
            }

            $endpoint = $report->getEndpoint();
            PushSubscription::query()->where('endpoint', $endpoint)->delete();
        }
    }
}
