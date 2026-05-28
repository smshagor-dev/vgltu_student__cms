<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomResetPasswordNotificationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_uses_custom_reset_password_notification(): void
    {
        Notification::fake();

        $user = (new User())->forceFill([
            'room_number' => 'T-101',
            'full_name' => 'Reset Test User',
            'email' => 'reset_'.time().'@example.com',
            'password' => bcrypt('secret123'),
            'mobile_number' => '+70000000000',
            'country' => 'Bangladesh',
            'religion' => 'Muslim',
            'gender' => 'Male',
            'date_of_birth' => '2000-01-01',
            'course_type' => 'BSC',
            'department' => 'Automobile',
            'course_year' => '1st Year',
            'course_language' => 'English',
        ]);
        $user->save();

        $user->sendPasswordResetNotification('sample-token');

        Notification::assertSentTo($user, CustomResetPasswordNotification::class);
    }
}
