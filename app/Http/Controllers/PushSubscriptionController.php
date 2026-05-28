<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'publicKey' => 'nullable|string',
            'authToken' => 'nullable|string',
            'contentEncoding' => 'nullable|string',
        ]);

        $user = $request->user();

        if (! $user) {
            throw ValidationException::withMessages([
                'user' => 'You must be logged in to enable browser notifications.',
            ]);
        }

        $subscription = $user->updatePushSubscription(
            $data['endpoint'],
            $data['publicKey'] ?? null,
            $data['authToken'] ?? null,
            $data['contentEncoding'] ?? null,
        );

        $subscription->forceFill([
            'user_agent' => (string) $request->userAgent(),
        ])->save();

        $user->forceFill(['browser_notifications_enabled' => true])->save();

        return response()->json([
            'success' => true,
            'message' => 'Browser notifications enabled successfully.',
        ]);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $user = $request->user();

        if (! $user) {
            throw ValidationException::withMessages([
                'user' => 'You must be logged in to disable browser notifications.',
            ]);
        }

        $user->deletePushSubscription($data['endpoint']);

        if (! $user->pushSubscriptions()->exists()) {
            $user->forceFill(['browser_notifications_enabled' => false])->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Browser notifications disabled successfully.',
        ]);
    }

    public function subscribe(Request $request)
    {
        $payload = $request->input('subscription', []);

        $request->merge([
            'endpoint' => $payload['endpoint'] ?? null,
            'publicKey' => $payload['keys']['p256dh'] ?? null,
            'authToken' => $payload['keys']['auth'] ?? null,
            'contentEncoding' => $payload['contentEncoding'] ?? null,
        ]);

        return $this->store($request);
    }

    public function unsubscribe(Request $request)
    {
        return $this->destroy($request);
    }
}
