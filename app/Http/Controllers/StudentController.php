<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageCompressor;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::query()
            ->latest()
            ->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'degree' => 'required|array',
            'department' => 'required|array',
            'pass_year' => 'required|array',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
        ]);
    
        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'students');
        }
    
        Student::create([
            'name' => $request->name,
            'photo_path' => $photoPath,
            'degree' => json_encode($request->degree),
            'department' => json_encode($request->department),
            'pass_year' => json_encode($request->pass_year),
        ]);
    
        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }
    
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }
    
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'degree' => 'required|array',
            'department' => 'required|array',
            'pass_year' => 'required|array',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
        ]);
    
        if ($request->hasFile('photo')) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'students');
            $student->photo_path = $photoPath;
        }
    
        $student->update([
            'name' => $request->name,
            'degree' => json_encode($request->degree),
            'department' => json_encode($request->department),
            'pass_year' => json_encode($request->pass_year),
        ]);
    
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }


    public function destroy(Student $student)
    {
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
