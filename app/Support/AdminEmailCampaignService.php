<?php

namespace App\Support;

use App\Jobs\SendAdminNotificationEmailJob;
use App\Models\NotificationEmailCampaign;
use App\Models\NotificationEmailRecipient;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminEmailCampaignService
{
    public static function resolveRecipients(array $validated): Collection
    {
        $query = User::query()->select('id', 'full_name', 'email');

        return match ($validated['recipient_type']) {
            'all' => $query->orderBy('id')->get(),
            'single' => $query->whereKey($validated['user_id'])->get(),
            'multiple' => $query->whereIn('id', $validated['user_ids'] ?? [])->orderBy('full_name')->get(),
        };
    }

    public static function queueCampaign(array $payload, Collection $recipients): NotificationEmailCampaign
    {
        $campaign = NotificationEmailCampaign::create([
            'created_by_admin_id' => $payload['created_by_admin_id'] ?? null,
            'recipient_type' => $payload['recipient_type'],
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'body_html' => $payload['body_html'] ?? null,
            'url' => $payload['url'] ?? route('home'),
            'total_recipients' => $recipients->count(),
            'status' => 'queued',
        ]);

        $now = now();

        $recipientRows = $recipients->values()->map(function ($user, int $index) use ($campaign, $now) {
            return [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => 'pending',
                'queued_for' => $now->copy()->addMinutes((int) floor($index / 2)),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        NotificationEmailRecipient::insert($recipientRows);

        NotificationEmailRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->orderBy('id')
            ->get(['id', 'queued_for'])
            ->each(function (NotificationEmailRecipient $recipient) {
                SendAdminNotificationEmailJob::dispatch($recipient->id)
                    ->delay($recipient->queued_for);
            });

        return $campaign;
    }
}
