<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateWebPushKeys extends Command
{
    protected $signature = 'webpush:generate-keys';

    protected $description = 'Generate VAPID keys for Web Push notifications.';

    public function handle(): int
    {
        try {
            $keys = VAPID::createVapidKeys();
        } catch (\Throwable $exception) {
            $this->error('Unable to generate VAPID keys with the current PHP/OpenSSL setup.');
            $this->line('Fallback: run `npx web-push generate-vapid-keys --json` and copy the keys into your .env file.');
            return self::FAILURE;
        }

        $this->info('Add these values to your .env file:');
        $this->line('WEBPUSH_PUBLIC_KEY=' . $keys['publicKey']);
        $this->line('WEBPUSH_PRIVATE_KEY=' . $keys['privateKey']);
        $this->line('WEBPUSH_SUBJECT=mailto:your-email@example.com');

        return self::SUCCESS;
    }
}
