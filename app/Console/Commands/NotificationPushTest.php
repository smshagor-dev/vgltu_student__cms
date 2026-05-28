<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\UserNotificationPublisher;
use Illuminate\Console\Command;

class NotificationPushTest extends Command
{
    protected $signature = 'notification:push-test {user_id : The user ID to receive the test push notification}';

    protected $description = 'Send a test browser push notification to a selected user.';

    public function handle(): int
    {
        $user = User::find($this->argument('user_id'));

        if (! $user) {
            $this->error('User not found.');

            return self::FAILURE;
        }

        UserNotificationPublisher::sendToUser($user->id, [
            'type' => 'push_test',
            'title' => 'Test Notification',
            'description' => 'Browser push notification working successfully.',
            'url' => route('home'),
            'icon' => 'fas fa-bell',
        ]);

        $this->info('Test push notification dispatched to user #' . $user->id . '.');

        return self::SUCCESS;
    }
}
