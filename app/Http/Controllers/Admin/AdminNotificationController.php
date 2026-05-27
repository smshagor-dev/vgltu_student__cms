<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use App\Support\UserNotificationPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = UserNotification::query()
            ->leftJoin('admins', 'admins.id', '=', 'user_notifications.created_by_admin_id')
            ->select([
                'user_notifications.created_by_admin_id',
                'user_notifications.type',
                'user_notifications.title',
                'user_notifications.description',
                'user_notifications.url',
                'user_notifications.icon',
                'user_notifications.created_at',
                'admins.name as admin_name',
            ])
            ->selectRaw('COUNT(*) as recipient_count')
            ->selectRaw('SUM(CASE WHEN user_notifications.read_at IS NOT NULL THEN 1 ELSE 0 END) as read_count')
            ->groupBy([
                'user_notifications.created_by_admin_id',
                'user_notifications.type',
                'user_notifications.title',
                'user_notifications.description',
                'user_notifications.url',
                'user_notifications.icon',
                'user_notifications.created_at',
                'admins.name',
            ])
            ->orderByDesc('user_notifications.created_at')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create(Request $request)
    {
        $selectedUser = null;

        if ($request->filled('user_id')) {
            $selectedUser = User::select('id', 'full_name', 'email')->find($request->integer('user_id'));
        }

        $users = User::query()
            ->select('id', 'full_name', 'email')
            ->orderBy('full_name')
            ->get();

        return view('admin.notifications.create', compact('users', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:all,single',
            'user_id' => 'nullable|required_if:recipient_type,single|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $payload = [
            'created_by_admin_id' => Auth::guard('admin')->id(),
            'type' => 'admin_notice',
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'url' => $validated['url'] ?? route('home'),
            'icon' => $validated['icon'] ?: 'fas fa-bell',
        ];

        if ($validated['recipient_type'] === 'single') {
            $user = User::findOrFail($validated['user_id']);

            UserNotificationPublisher::sendToUser($user->id, $payload);

            return redirect()
                ->route('admin.notifications.create', ['user_id' => $user->id])
                ->with('success', 'Notification sent to ' . $user->full_name . '.');
        }

        UserNotificationPublisher::broadcastToUsers($payload);

        return redirect()
            ->route('admin.notifications.create')
            ->with('success', 'Notification sent to all users.');
    }
}
