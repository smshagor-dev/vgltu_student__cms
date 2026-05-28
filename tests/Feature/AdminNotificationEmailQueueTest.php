<?php

namespace Tests\Feature;

use App\Jobs\SendAdminNotificationEmailJob;
use App\Models\Admin;
use App\Models\NotificationEmailCampaign;
use App\Models\NotificationEmailRecipient;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminNotificationEmailQueueTest extends TestCase
{
    use DatabaseTransactions;

    public function test_portal_notifications_are_sent_without_creating_email_campaigns(): void
    {
        Carbon::setTestNow('2026-05-29 12:00:00');
        Queue::fake();
        Notification::fake();

        $admin = Admin::create([
            'name' => 'Queue Admin',
            'email' => 'queue-admin@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $users = collect([
            $this->makeUser('first@example.com', 'First User'),
            $this->makeUser('second@example.com', 'Second User'),
            $this->makeUser('third@example.com', 'Third User'),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.notifications.store'), [
            'recipient_type' => 'multiple',
            'user_ids' => $users->pluck('id')->all(),
            'title' => 'Hostel update',
            'description' => 'Check the updated hostel notice board.',
            'url' => route('home'),
            'icon' => 'fas fa-bell',
        ]);

        $response->assertRedirect(route('admin.notifications.create'));

        $campaign = NotificationEmailCampaign::query()->latest('id')->first();

        $this->assertNull($campaign);
        $this->assertDatabaseCount('user_notifications', 3);
        Queue::assertNothingPushed();

        Carbon::setTestNow();
    }

    public function test_email_job_marks_campaign_as_completed_after_last_recipient(): void
    {
        Mail::fake();

        $user = $this->makeUser('job-user@example.com', 'Job User');

        $campaign = NotificationEmailCampaign::create([
            'recipient_type' => 'single',
            'title' => 'Exam routine changed',
            'description' => 'Please log in and check the latest update.',
            'url' => route('home'),
            'total_recipients' => 1,
            'status' => 'queued',
        ]);

        $recipient = NotificationEmailRecipient::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'status' => 'pending',
            'queued_for' => now(),
        ]);

        (new SendAdminNotificationEmailJob($recipient->id))->handle();

        $recipient->refresh();
        $campaign->refresh();

        $this->assertSame('sent', $recipient->status);
        $this->assertNotNull($recipient->sent_at);
        $this->assertSame(1, $campaign->sent_count);
        $this->assertSame('completed', $campaign->status);
        $this->assertNotNull($campaign->completed_at);
    }

    private function makeUser(string $email, string $name): User
    {
        $user = new User();
        $user->forceFill([
            'room_number' => 'A-101',
            'full_name' => $name,
            'email' => $email,
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
            'approved' => true,
        ]);
        $user->save();

        return $user;
    }
}
