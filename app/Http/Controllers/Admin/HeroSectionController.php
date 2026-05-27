<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroFlag;
use App\Models\HeroImage;
use App\Models\HeroSection;
use App\Support\ImageCompressor;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function edit()
    {
        $hero = HeroSection::query()->with(['flags', 'images'])->first() ?? new HeroSection([
            'badge_text' => 'Trusted Study Abroad Guidance',
            'title' => 'Your future starts with the right international education pathway.',
            'subtitle' => 'Explore university options, compare countries, and take the next step with a student-focused advisory experience.',
            'cta_text' => 'Start Your Journey',
            'cta_link' => route('register'),
            'overlay_start_color' => '#1b1033',
            'overlay_end_color' => '#d14b84',
            'overlay_opacity' => 0.68,
            'is_active' => true,
        ]);

        return view('admin.homepage.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'badge_text' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:1000',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
            'background_images' => 'nullable|array',
            'background_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
            'existing_images' => 'nullable|array',
            'existing_images.*.id' => 'nullable|integer|exists:hero_images,id',
            'existing_images.*.remove' => 'nullable|boolean',
            'existing_images.*.sort_order' => 'nullable|integer|min:0|max:999',
            'cta_text' => 'required|string|max:100',
            'cta_link' => 'required|string|max:255',
            'overlay_start_color' => 'required|string|max:20',
            'overlay_end_color' => 'required|string|max:20',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'is_active' => 'nullable|boolean',
            'flags' => 'nullable|array',
            'flags.*.label' => 'nullable|string|max:80',
            'flags.*.position_top' => 'nullable|integer|min:0|max:100',
            'flags.*.position_left' => 'nullable|integer|min:0|max:100',
            'flags.*.sort_order' => 'nullable|integer|min:0|max:999',
            'flags.*.is_active' => 'nullable|boolean',
            'flags.*.existing_image_path' => 'nullable|string|max:255',
            'flag_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $hero = HeroSection::query()->first() ?? new HeroSection();

        if ($request->hasFile('background_image')) {
            $data['background_image_path'] = ImageCompressor::storeUploadedFile($request->file('background_image'), 'homepage/hero');
        }

        $hero->fill($data + ['is_active' => $request->boolean('is_active')])->save();

        foreach ($request->input('existing_images', []) as $imageData) {
            $image = HeroImage::query()
                ->where('hero_section_id', $hero->id)
                ->find($imageData['id'] ?? null);

            if (! $image) {
                continue;
            }

            if (! empty($imageData['remove'])) {
                $image->delete();
                continue;
            }

            $image->update([
                'sort_order' => (int) ($imageData['sort_order'] ?? $image->sort_order),
            ]);
        }

        foreach ($request->file('background_images', []) as $index => $imageFile) {
            if (! $imageFile) {
                continue;
            }

            HeroImage::create([
                'hero_section_id' => $hero->id,
                'image_path' => ImageCompressor::storeUploadedFile($imageFile, 'homepage/hero/gallery'),
                'sort_order' => HeroImage::query()->where('hero_section_id', $hero->id)->max('sort_order') + $index + 1,
            ]);
        }

        $hero->flags()->delete();

        foreach ($request->input('flags', []) as $index => $flagData) {
            $label = trim((string) ($flagData['label'] ?? ''));
            $hasFile = $request->hasFile("flag_images.$index");

            if ($label === '' && ! $hasFile) {
                continue;
            }

            HeroFlag::create([
                'hero_section_id' => $hero->id,
                'label' => $label ?: 'Flag',
                'image_path' => $hasFile
                    ? ImageCompressor::storeUploadedFile($request->file("flag_images.$index"), 'homepage/flags')
                    : ($flagData['existing_image_path'] ?? null),
                'position_top' => (int) ($flagData['position_top'] ?? 50),
                'position_left' => (int) ($flagData['position_left'] ?? 50),
                'sort_order' => (int) ($flagData['sort_order'] ?? $index),
                'is_active' => isset($flagData['is_active']) ? (bool) $flagData['is_active'] : true,
            ]);
        }

        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.hero.edit')->with('success', 'Hero section updated successfully.');
    }
}
