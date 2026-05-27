<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyDestination;
use App\Support\ImageCompressor;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudyDestinationController extends Controller
{
    public function index()
    {
        $destinations = StudyDestination::query()
            ->orderBy('display_order')
            ->paginate(20);

        return view('admin.homepage.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.homepage.destinations.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['flag_image_path'] = ImageCompressor::storeUploadedFile($request->file('flag_image'), 'homepage/destinations');
        StudyDestination::create($data);
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.destinations.index')->with('success', 'Study destination created successfully.');
    }

    public function edit(StudyDestination $destination)
    {
        return view('admin.homepage.destinations.edit', compact('destination'));
    }

    public function update(Request $request, StudyDestination $destination)
    {
        $data = $this->validated($request, $destination, false);

        if ($request->hasFile('flag_image')) {
            $data['flag_image_path'] = ImageCompressor::storeUploadedFile($request->file('flag_image'), 'homepage/destinations');
        }

        $destination->update($data);
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.destinations.index')->with('success', 'Study destination updated successfully.');
    }

    public function destroy(StudyDestination $destination)
    {
        $destination->delete();
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.destinations.index')->with('success', 'Study destination deleted successfully.');
    }

    private function validated(Request $request, ?StudyDestination $destination = null, bool $imageRequired = true): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'slug' => ['required', 'string', 'max:120', Rule::unique('study_destinations', 'slug')->ignore($destination?->id)],
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        $rules['flag_image'] = $imageRequired
            ? 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            : 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

        return $request->validate($rules) + [
            'display_order' => (int) $request->input('display_order', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
