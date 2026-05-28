<?php

namespace App\Support;

use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\UserPushNotification;
use Illuminate\Support\Facades\Notification;

class UserNotificationPublisher
{
    public static function broadcastToUsers(array $payload): void
    {
        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            return;
        }

        $baseData = [
            'created_by_admin_id' => $payload['created_by_admin_id'] ?? null,
            'type' => $payload['type'] ?? 'general',
            'title' => $title,
            'description' => $payload['description'] ?? null,
            'url' => $payload['url'] ?? null,
            'icon' => $payload['icon'] ?? 'fas fa-bell',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        User::query()->select('id')->chunkById(200, function ($users) use ($baseData, $payload) {
            $rows = $users->map(function ($user) use ($baseData) {
                return array_merge($baseData, [
                    'user_id' => $user->id,
                ]);
            })->all();

            UserNotification::insert($rows);
            Notification::send($users, self::makePushNotification($payload));
        });
    }

    public static function sendToUsers(iterable $userIds, array $payload): void
    {
        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            return;
        }

        $rows = collect($userIds)
            ->filter()
            ->unique()
            ->values()
            ->map(function ($userId) use ($payload, $title) {
                return [
                    'user_id' => $userId,
                    'created_by_admin_id' => $payload['created_by_admin_id'] ?? null,
                    'type' => $payload['type'] ?? 'general',
                    'title' => $title,
                    'description' => $payload['description'] ?? null,
                    'url' => $payload['url'] ?? null,
                    'icon' => $payload['icon'] ?? 'fas fa-bell',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->all();

        if ($rows === []) {
            return;
        }

        UserNotification::insert($rows);

        $users = User::query()
            ->whereIn('id', collect($rows)->pluck('user_id')->all())
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, self::makePushNotification($payload));
        }
    }

    public static function sendToUser(int $userId, array $payload): void
    {
        self::sendToUsers([$userId], $payload);
    }

    private static function makePushNotification(array $payload): UserPushNotification
    {
        return new UserPushNotification(
            trim((string) ($payload['title'] ?? 'Notification')),
            (string) ($payload['description'] ?? 'Open the portal to view this notification.'),
            (string) ($payload['type'] ?? 'general'),
            $payload['url'] ?? route('home'),
            $payload['icon'] ?? 'fas fa-bell',
        );
    }
}
