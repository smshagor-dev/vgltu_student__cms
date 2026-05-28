<?php

namespace App\Support;

class WebPushService
{
    public static function isConfigured(): bool
    {
        return filled(config('webpush.vapid.subject'))
            && filled(config('webpush.vapid.public_key'))
            && filled(config('webpush.vapid.private_key'));
    }

    public static function publicKey(): ?string
    {
        return config('webpush.vapid.public_key');
    }
}
