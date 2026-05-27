<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function feed()
    {
        $user = Auth::user();
        $notifications = $user
            ? $user->userNotifications()->take(20)->get()
            : collect();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'icon' => $notification->icon ?: 'far fa-bell',
                    'open_url' => route('notifications.open', $notification),
                    'read_at' => optional($notification->read_at)?->toISOString(),
                    'created_at' => optional($notification->created_at)?->format('d M Y, h:i A'),
                ];
            })->values(),
            'unread_count' => $notifications->whereNull('read_at')->count(),
        ]);
    }

    public function open(UserNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        if ($notification->read_at === null) {
            $notification->forceFill(['read_at' => now()])->save();
        }

        return redirect($notification->url ?: route('home'));
    }

    public function markRead(UserNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        if ($notification->read_at === null) {
            $notification->forceFill(['read_at' => now()])->save();
        }

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        Auth::user()?->userNotifications()->whereNull('read_at')->update([
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function browserPreference(Request $request)
    {
        $data = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $user = Auth::user();
        $user->forceFill([
            'browser_notifications_enabled' => $data['enabled'],
        ])->save();

        return response()->json(['success' => true]);
    }
}
