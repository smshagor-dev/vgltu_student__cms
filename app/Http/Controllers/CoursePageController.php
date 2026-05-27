<?php

namespace App\Http\Controllers;

use App\Models\HomepageCourse;

class CoursePageController extends Controller
{
    public function index()
    {
        $courses = HomepageCourse::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();

        return view('courses', compact('courses'));
    }

    public function show(HomepageCourse $course)
    {
        abort_unless($course->is_active, 404);

        return view('course-show', compact('course'));
    }
}
