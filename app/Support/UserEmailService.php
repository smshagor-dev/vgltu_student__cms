<?php

namespace App\Support;

use App\Models\Campaign;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserEmailService
{
    public static function sendRegistrationPending(User $user, string $plainPassword): bool
    {
        return self::send(
            $user->email,
            'Registration Successful - Pending Approval',
            view('emails.registration-pending', [
                'user' => $user,
                'plainPassword' => $plainPassword,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    public static function sendApproval(User $user): bool
    {
        return self::send(
            $user->email,
            'Congratulations! Your Account Has Been Approved',
            view('emails.user-approved', [
                'user' => $user,
                'plainPassword' => $user->registration_password_plain,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    public static function sendRejection(User $user): bool
    {
        return self::send(
            $user->email,
            'Registration Update',
            view('emails.user-rejected', [
                'user' => $user,
            ])->render()
        );
    }

    public static function sendCampaignAnnouncement(User $user, Campaign $campaign, string $notificationTitle, string $notificationDescription): bool
    {
        return self::send(
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

    public static function sendVisaOverdueReminder(User $user, CarbonInterface $expiryDate, string $editUrl): bool
    {
        return self::send(
            $user->email,
            'Urgent: Update your expired visa information',
            view('emails.visa-overdue-reminder', [
                'user' => $user,
                'expiryDate' => $expiryDate,
                'editUrl' => $editUrl,
            ])->render()
        );
    }

    public static function sendPasswordReset(User $user, string $plainPassword): bool
    {
        return self::send(
            $user->email,
            'Your account password has been reset',
            view('emails.user-password-reset', [
                'user' => $user,
                'plainPassword' => $plainPassword,
                'loginUrl' => route('login'),
            ])->render()
        );
    }

    public static function sendAdminNotification(User $user, string $title, ?string $description = null, ?string $actionUrl = null): bool
    {
        return self::send(
            $user->email,
            $title,
            view('emails.admin-notification', [
                'user' => $user,
                'title' => $title,
                'description' => $description,
                'actionUrl' => $actionUrl ?: route('home'),
            ])->render()
        );
    }

    public static function sendAdminEmailCampaign(User $user, string $title, string $bodyHtml, ?string $actionUrl = null): bool
    {
        return self::send(
            $user->email,
            $title,
            view('emails.admin-email-campaign', [
                'user' => $user,
                'title' => $title,
                'bodyHtml' => $bodyHtml,
                'actionUrl' => $actionUrl ?: route('home'),
            ])->render()
        );
    }

    private static function send(?string $email, string $subject, string $html): bool
    {
        if (! $email) {
            return false;
        }

        try {
            Mail::html($html, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            return true;
        } catch (\Throwable $exception) {
            Log::error('Unable to send user email.', [
                'email' => $email,
                'subject' => $subject,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
