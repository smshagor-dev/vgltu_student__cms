<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->withFragment('contact');
        }

        $validated = $validator->validated();

        ContactMessage::create($validated);

        return redirect()
            ->back()
            ->withFragment('contact')
            ->with('contact_success', 'Your message has been sent successfully.');
    }

    public function adminIndex()
    {
        $messages = ContactMessage::query()
            ->latest()
            ->paginate(20);

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.contact_messages.index', compact('messages', 'unreadCount'));
    }

    public function adminShow(ContactMessage $contactMessage)
    {
        if (!$contactMessage->is_read) {
            $contactMessage->forceFill(['is_read' => true])->save();
        }

        return view('admin.contact_messages.show', compact('contactMessage'));
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted successfully.');
    }
}
