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
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminEmailNotificationCampaignTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_queue_dedicated_email_campaign_for_multiple_users(): void
    {
        Carbon::setTestNow('2026-05-29 12:00:00');
        Queue::fake();

        $admin = Admin::create([
            'name' => 'Email Admin',
            'email' => 'email-admin@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $users = collect([
            $this->makeUser('first-email@example.com', 'First Email User'),
            $this->makeUser('second-email@example.com', 'Second Email User'),
            $this->makeUser('third-email@example.com', 'Third Email User'),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.email-notifications.store'), [
            'recipient_type' => 'multiple',
            'user_ids' => $users->pluck('id')->all(),
            'title' => 'Hostel Email Update',
            'description' => 'Short campaign summary',
            'body_html' => '<p><strong>Hello students,</strong></p><p>Please check the latest hostel update.</p>',
            'url' => route('home'),
        ]);

        $response->assertRedirect(route('admin.email-notifications.create'));

        $campaign = NotificationEmailCampaign::query()->latest('id')->first();

        $this->assertNotNull($campaign);
        $this->assertSame(3, $campaign->total_recipients);
        $this->assertSame('queued', $campaign->status);
        $this->assertStringContainsString('<strong>Hello students,</strong>', (string) $campaign->body_html);
        $this->assertDatabaseCount('notification_email_recipients', 3);

        Queue::assertPushed(SendAdminNotificationEmailJob::class, 3);

        $recipients = NotificationEmailRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->orderBy('id')
            ->get();

        $this->assertTrue($recipients[0]->queued_for->equalTo(Carbon::now()));
        $this->assertTrue($recipients[1]->queued_for->equalTo(Carbon::now()));
        $this->assertTrue($recipients[2]->queued_for->equalTo(Carbon::now()->copy()->addMinute()));

        Carbon::setTestNow();
    }

    public function test_email_job_marks_campaign_failed_when_mailer_returns_false(): void
    {
        Mail::shouldReceive('html')
            ->once()
            ->andThrow(new \RuntimeException('SMTP failure'));

        $user = $this->makeUser('job-failure@example.com', 'Failure User');

        $campaign = NotificationEmailCampaign::create([
            'recipient_type' => 'single',
            'title' => 'Email Failure Test',
            'description' => 'Failure path',
            'body_html' => '<p>Body</p>',
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

        $this->assertSame('failed', $recipient->status);
        $this->assertNotNull($recipient->failed_at);
        $this->assertSame(1, $campaign->failed_count);
        $this->assertSame('completed_with_errors', $campaign->status);
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
