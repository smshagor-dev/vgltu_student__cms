<?php

namespace App\Support;

use App\Models\User;

class StudentDataStatus
{
    public static function ensureCompletionReminder(User $user): void
    {
        if ($user->studentsData()->exists()) {
            return;
        }

        $exists = $user->userNotifications()
            ->where('type', 'student_data_required')
            ->exists();

        if ($exists) {
            return;
        }

        $actionUrl = route('students_data.create');

        UserNotificationPublisher::sendToUser($user->id, [
            'type' => 'student_data_required',
            'title' => 'Complete your student document data',
            'description' => 'Passport, visa, and green card information is missing. Please update your student data now.',
            'url' => $actionUrl,
            'icon' => 'fas fa-file-circle-exclamation',
        ]);

        UserEmailService::sendStudentDataRequired($user, $actionUrl);
    }
}
