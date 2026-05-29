<?php

namespace App\Console\Commands;

use App\Models\StudentsData;
use App\Support\UserEmailService;
use App\Support\UserNotificationPublisher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendVisaExpiryNotifications extends Command
{
    protected $signature = 'visa:send-reminders';

    protected $description = 'Send visa expiry reminders and overdue alerts to users.';

    public function handle(): int
    {
        $today = Carbon::today();

        StudentsData::query()
            ->with('user')
            ->whereNotNull('visa_expiry_date')
            ->chunkById(200, function ($records) use ($today) {
                foreach ($records as $record) {
                    $user = $record->user;
                    if (! $user) {
                        continue;
                    }

                    $expiryDate = Carbon::parse($record->visa_expiry_date)->startOfDay();
                    $daysUntilExpiry = $today->diffInDays($expiryDate, false);

                    $reminders = [
                        90 => 'visa_reminder_90_sent_at',
                        75 => 'visa_reminder_75_sent_at',
                        60 => 'visa_reminder_60_sent_at',
                    ];

                    foreach ($reminders as $days => $column) {
                        if ($daysUntilExpiry <= $days && $daysUntilExpiry >= 0 && $record->{$column} === null) {
                            UserNotificationPublisher::sendToUser($user->id, [
                                'type' => 'visa_expiry_reminder',
                                'title' => "Visa expiry reminder: {$days} days left",
                                'description' => 'Your visa will expire on ' . $expiryDate->format('d M Y') . '. Please update your record before the expiry date.',
                                'url' => route('students_data.edit', $record->id),
                                'icon' => 'fas fa-passport',
                            ]);

                            $record->{$column} = now();
                            $record->save();
                        }
                    }

                    $isOverdueByTenDays = $daysUntilExpiry <= -10;
                    $lastOverdueReminderAt = $record->visa_overdue_10_sent_at?->copy()?->startOfDay();

                    if ($isOverdueByTenDays && ($lastOverdueReminderAt === null || $lastOverdueReminderAt->lt($today))) {
                        $title = 'Your visa expaired update info';
                        $description = 'Your visa expiry date was ' . $expiryDate->format('d M Y') . '. Please update your visa information immediately.';

                        UserNotificationPublisher::sendToUser($user->id, [
                            'type' => 'visa_expiry_overdue',
                            'title' => $title,
                            'description' => $description,
                            'url' => route('students_data.edit', $record->id),
                            'icon' => 'fas fa-triangle-exclamation',
                        ]);

                        UserEmailService::sendVisaOverdueReminder($user, $expiryDate, route('students_data.edit', $record->id));

                        $record->visa_overdue_10_sent_at = now();
                        $record->save();
                    }
                }
            });

        $this->info('Visa reminder notifications processed successfully.');

        return self::SUCCESS;
    }
}
