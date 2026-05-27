<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Support\ImageCompressor;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function edit()
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting([
            'site_name' => 'Global Study Gateway',
            'contact_button_text' => 'Book Consultation',
            'contact_button_link' => '/contact-us',
            'topbar_location' => 'Voronezh, Russian Federation',
            'class_routine_text' => 'Class Routine',
            'class_routine_link' => '/class_routine',
            'university_profile_text' => 'University Profile',
            'university_profile_link' => '/university_profile',
        ]);

        return view('admin.homepage.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:2048',
            'contact_button_text' => 'required|string|max:100',
            'contact_button_link' => 'required|string|max:255',
            'topbar_location' => 'required|string|max:255',
            'class_routine_text' => 'required|string|max:100',
            'class_routine_link' => 'required|string|max:255',
            'university_profile_text' => 'required|string|max:100',
            'university_profile_link' => 'required|string|max:255',
        ]);

        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = ImageCompressor::storeUploadedFile($request->file('logo'), 'homepage/settings');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon_path'] = ImageCompressor::storeUploadedFile($request->file('favicon'), 'homepage/settings');
        }

        $settings->fill($data)->save();

        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.settings.edit')->with('success', 'Website settings updated successfully.');
    }
}
