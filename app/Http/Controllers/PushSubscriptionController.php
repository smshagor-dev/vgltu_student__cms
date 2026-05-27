<?php

namespace App\Http\Controllers;

use App\Support\WebPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|string',
            'subscription.keys' => 'required|array',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
            'subscription.contentEncoding' => 'nullable|string',
        ]);

        WebPushService::subscribe(Auth::user(), $data['subscription'], $request->userAgent());

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'nullable|string',
        ]);

        WebPushService::unsubscribe(Auth::user(), $data['endpoint'] ?? null);

        return response()->json(['success' => true]);
    }
}
