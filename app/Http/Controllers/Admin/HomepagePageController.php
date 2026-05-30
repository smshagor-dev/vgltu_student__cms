<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCourse;
use App\Models\WebsiteSetting;
use App\Support\ImageCompressor;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;

class HomepagePageController extends Controller
{
    public function editAboutUniversity()
    {
        $settings = $this->settings();

        return view('admin.homepage.pages.edit', [
            'pageKey' => 'about_university',
            'pageLabel' => 'Universities',
            'settings' => $settings,
        ]);
    }

    public function updateAboutUniversity(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $settings = $this->settings();
        $settings->about_university_title = $data['title'] ?? null;
        $settings->about_university_content = $data['content'] ?? null;

        if ($request->hasFile('header_image')) {
            $settings->about_university_header_path = ImageCompressor::storeUploadedFile($request->file('header_image'), 'homepage/about-university');
        }

        $settings->save();
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.pages.about-university.edit')->with('success', 'Universities page updated successfully.');
    }

    public function editCourses()
    {
        $settings = $this->settings();

        return view('admin.homepage.pages.edit', [
            'pageKey' => 'courses',
            'pageLabel' => 'Department Page',
            'settings' => $settings,
            'courses' => HomepageCourse::query()->orderBy('display_order')->orderBy('title')->get(),
        ]);
    }

    public function updateCourses(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $settings = $this->settings();
        $settings->courses_title = $data['title'] ?? null;
        $settings->courses_content = $data['content'] ?? null;

        if ($request->hasFile('header_image')) {
            $settings->courses_header_path = ImageCompressor::storeUploadedFile($request->file('header_image'), 'homepage/courses');
        }

        $settings->save();
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.pages.courses.edit')->with('success', 'Department page updated successfully.');
    }

    private function settings(): WebsiteSetting
    {
        return WebsiteSetting::query()->first() ?? new WebsiteSetting([
            'about_university_menu_text' => 'Universities',
            'about_university_title' => 'Universities',
            'courses_menu_text' => 'Department',
            'courses_title' => 'Department',
        ]);
    }
}
