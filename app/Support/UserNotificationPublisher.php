<?php

namespace App\Support;

use App\Models\User;
use App\Models\UserNotification;
use App\Support\WebPushService;

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

        User::query()->select('id')->chunkById(200, function ($users) use ($baseData) {
            $rows = $users->map(function ($user) use ($baseData) {
                return array_merge($baseData, [
                    'user_id' => $user->id,
                ]);
            })->all();

            UserNotification::insert($rows);
            WebPushService::sendToUsers($users->pluck('id')->all(), $payload);
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
        WebPushService::sendToUsers(collect($rows)->pluck('user_id')->all(), $payload);
    }

    public static function sendToUser(int $userId, array $payload): void
    {
        self::sendToUsers([$userId], $payload);
    }
}
