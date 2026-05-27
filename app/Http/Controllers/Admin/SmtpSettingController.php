<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class SmtpSettingController extends Controller
{
    public function edit()
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting([
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
        ]);

        return view('admin.smtp.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'smtp_enabled' => 'nullable|boolean',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|max:20',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ]);

        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting();
        $settings->smtp_enabled = $request->boolean('smtp_enabled');
        $settings->smtp_host = $data['smtp_host'] ?? null;
        $settings->smtp_port = $data['smtp_port'] ?? null;
        $settings->smtp_username = $data['smtp_username'] ?? null;

        if ($request->filled('smtp_password')) {
            $settings->smtp_password = $data['smtp_password'];
        }

        $settings->smtp_encryption = $data['smtp_encryption'] ?? null;
        $settings->smtp_from_address = $data['smtp_from_address'] ?? null;
        $settings->smtp_from_name = $data['smtp_from_name'] ?? null;
        $settings->save();

        return redirect()->route('admin.smtp.edit')->with('success', 'SMTP settings updated successfully.');
    }
}
