<?php

namespace App\Support;

use App\Models\Campaign;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserEmailService
{
    public static function sendRegistrationPending(User $user, string $plainPassword): void
    {
        self::send(
            $user->email,
            'Registration Successful - Pending Approval',
            view('emails.registration-pending', [
                'user' => $user,
                'plainPassword' => $plainPassword,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    public static function sendApproval(User $user): void
    {
        self::send(
            $user->email,
            'Congratulations! Your Account Has Been Approved',
            view('emails.user-approved', [
                'user' => $user,
                'plainPassword' => $user->registration_password_plain,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    public static function sendRejection(User $user): void
    {
        self::send(
            $user->email,
            'Registration Update',
            view('emails.user-rejected', [
                'user' => $user,
            ])->render()
        );
    }

    public static function sendCampaignAnnouncement(User $user, Campaign $campaign, string $notificationTitle, string $notificationDescription): void
    {
        self::send(
            $user->email,
            $notificationTitle,
            view('emails.campaign-announcement', [
                'user' => $user,
                'campaign' => $campaign,
                'notificationTitle' => $notificationTitle,
                'notificationDescription' => $notificationDescription,
                'campaignUrl' => route('campaigns.show', $campaign),
            ])->render()
        );
    }

    public static function sendVisaOverdueReminder(User $user, CarbonInterface $expiryDate, string $editUrl): void
    {
        self::send(
            $user->email,
            'Urgent: Update your expired visa information',
            view('emails.visa-overdue-reminder', [
                'user' => $user,
                'expiryDate' => $expiryDate,
                'editUrl' => $editUrl,
            ])->render()
        );
    }

    public static function sendPasswordReset(User $user, string $plainPassword): void
    {
        self::send(
            $user->email,
            'Your account password has been reset',
            view('emails.user-password-reset', [
                'user' => $user,
                'plainPassword' => $plainPassword,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    private static function send(?string $email, string $subject, string $html): void
    {
        if (! $email) {
            return;
        }

        try {
            Mail::html($html, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
        } catch (\Throwable $exception) {
            Log::error('Unable to send user email.', [
                'email' => $email,
                'subject' => $subject,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
