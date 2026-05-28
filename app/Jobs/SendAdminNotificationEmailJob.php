<?php

namespace App\Jobs;

use App\Models\NotificationEmailCampaign;
use App\Models\NotificationEmailRecipient;
use App\Support\UserEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SendAdminNotificationEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(public int $recipientId)
    {
    }

    public function handle(): void
    {
        $recipient = NotificationEmailRecipient::query()
            ->with(['campaign', 'user'])
            ->find($this->recipientId);

        if (! $recipient || ! $recipient->campaign || ! $recipient->user || $recipient->status !== 'pending') {
            return;
        }

        try {
            $sent = UserEmailService::sendAdminEmailCampaign(
                $recipient->user,
                $recipient->campaign->title,
                $recipient->campaign->body_html ?: nl2br(e((string) $recipient->campaign->description)),
                $recipient->campaign->url
            );

            if (! $sent) {
                throw new \RuntimeException('The email could not be delivered by the configured mail transport.');
            }

            $recipient->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            $recipient->campaign()->update([
                'status' => 'sending',
                'started_at' => $recipient->campaign->started_at ?? now(),
                'last_sent_at' => now(),
            ]);

            NotificationEmailCampaign::query()
                ->whereKey($recipient->campaign_id)
                ->increment('sent_count');
        } catch (\Throwable $exception) {
            $recipient->update([
                'status' => 'failed',
                'failed_at' => now(),
                'error_message' => mb_substr($exception->getMessage(), 0, 1000),
            ]);

            $recipient->campaign()->update([
                'status' => 'sending',
                'started_at' => $recipient->campaign->started_at ?? now(),
            ]);

            NotificationEmailCampaign::query()
                ->whereKey($recipient->campaign_id)
                ->increment('failed_count');
        }

        $this->markCampaignCompleteIfFinished($recipient->campaign_id);
    }

    private function markCampaignCompleteIfFinished(int $campaignId): void
    {
        DB::transaction(function () use ($campaignId) {
            $campaign = NotificationEmailCampaign::query()
                ->lockForUpdate()
                ->find($campaignId);

            if (! $campaign) {
                return;
            }

            if (($campaign->sent_count + $campaign->failed_count) < $campaign->total_recipients) {
                return;
            }

            $campaign->update([
                'status' => $campaign->failed_count > 0 ? 'completed_with_errors' : 'completed',
                'completed_at' => now(),
            ]);
        });
    }
}
