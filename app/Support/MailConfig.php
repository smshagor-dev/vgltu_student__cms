<?php

namespace App\Support;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Schema;

class MailConfig
{
    public static function applyFromDatabase(): void
    {
        if (! Schema::hasTable('website_settings')) {
            return;
        }

        $settings = WebsiteSetting::query()->first();

        if (! $settings || ! $settings->smtp_enabled || ! $settings->smtp_host || ! $settings->smtp_from_address) {
            return;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $settings->smtp_host,
            'mail.mailers.smtp.port' => $settings->smtp_port ?: 587,
            'mail.mailers.smtp.username' => $settings->smtp_username,
            'mail.mailers.smtp.password' => $settings->smtp_password,
            'mail.mailers.smtp.encryption' => $settings->smtp_encryption ?: 'tls',
            'mail.from.address' => $settings->smtp_from_address,
            'mail.from.name' => $settings->smtp_from_name ?: config('app.name'),
        ]);
    }
}
