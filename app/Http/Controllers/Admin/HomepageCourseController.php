<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCourse;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomepageCourseController extends Controller
{
    public function create()
    {
        return view('admin.homepage.courses.create', [
            'course' => new HomepageCourse(),
        ]);
    }

    public function store(Request $request)
    {
        HomepageCourse::create($this->validated($request));
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.pages.courses.edit')->with('success', 'Course added successfully.');
    }

    public function edit(HomepageCourse $course)
    {
        return view('admin.homepage.courses.edit', compact('course'));
    }

    public function update(Request $request, HomepageCourse $course)
    {
        $course->update($this->validated($request, $course));
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.pages.courses.edit')->with('success', 'Course updated successfully.');
    }

    public function destroy(HomepageCourse $course)
    {
        $course->delete();
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.pages.courses.edit')->with('success', 'Course deleted successfully.');
    }

    private function validated(Request $request, ?HomepageCourse $course = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('homepage_courses', 'slug')->ignore($course?->id)],
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]) + [
            'display_order' => (int) $request->input('display_order', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
