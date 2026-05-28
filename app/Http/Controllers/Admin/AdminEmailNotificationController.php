<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationEmailCampaign;
use App\Models\User;
use App\Support\AdminEmailCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminEmailNotificationController extends Controller
{
    public function index()
    {
        $emailCampaigns = NotificationEmailCampaign::query()
            ->with('admin')
            ->latest()
            ->paginate(20);

        return view('admin.email_notifications.index', compact('emailCampaigns'));
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

        return view('admin.email_notifications.create', compact('users', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:all,single,multiple',
            'user_id' => 'nullable|required_if:recipient_type,single|exists:users,id',
            'user_ids' => 'nullable|required_if:recipient_type,multiple|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'body_html' => 'required|string',
            'url' => 'nullable|string|max:255',
        ]);

        $recipients = AdminEmailCampaignService::resolveRecipients($validated);

        if ($recipients->isEmpty()) {
            return back()->withErrors([
                'recipient_type' => 'No users found for the selected recipient type.',
            ])->withInput();
        }

        $bodyHtml = trim((string) $validated['body_html']);

        if ($bodyHtml === '' || trim(strip_tags($bodyHtml)) === '') {
            return back()->withErrors([
                'body_html' => 'Email body cannot be empty.',
            ])->withInput();
        }

        $campaign = AdminEmailCampaignService::queueCampaign([
            'created_by_admin_id' => Auth::guard('admin')->id(),
            'recipient_type' => $validated['recipient_type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?: Str::limit(trim(strip_tags($bodyHtml)), 180),
            'body_html' => $bodyHtml,
            'url' => $validated['url'] ?? route('home'),
        ], $recipients);

        $recipientCount = $recipients->count();
        $minutes = (int) ceil($recipientCount / 2);

        return redirect()
            ->route('admin.email-notifications.create', $validated['recipient_type'] === 'single' ? ['user_id' => $recipients->first()->id] : [])
            ->with('success', 'Email campaign #' . $campaign->id . ' queued for ' . $recipientCount . ' user' . ($recipientCount === 1 ? '' : 's') . '. It will send 2 emails per minute, so about ' . $minutes . ' minute' . ($minutes === 1 ? '' : 's') . ' may be needed.');
    }
}
