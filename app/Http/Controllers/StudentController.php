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
            ->latest('created_at')
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
            'degree.*' => 'required|string|max:255',
            'department' => 'required|array',
            'department.*' => 'required|string|max:255',
            'pass_year' => 'required|array',
            'pass_year.*' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
        ]);
    
        $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'students');
    
        Student::create([
            'name' => $request->name,
            'photo_path' => $photoPath,
            'degree' => array_values(array_filter($request->degree)),
            'department' => array_values(array_filter($request->department)),
            'pass_year' => array_values(array_filter($request->pass_year)),
            'status' => 'approved',
            'source' => 'admin',
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
            'degree.*' => 'required|string|max:255',
            'department' => 'required|array',
            'department.*' => 'required|string|max:255',
            'pass_year' => 'required|array',
            'pass_year.*' => 'required|string|max:255',
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
            'degree' => array_values(array_filter($request->degree)),
            'department' => array_values(array_filter($request->department)),
            'pass_year' => array_values(array_filter($request->pass_year)),
        ]);
    
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function submitAlumni(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'degree' => 'required|array|min:1',
            'degree.*' => 'required|string|max:255',
            'department' => 'required|array|min:1',
            'department.*' => 'required|string|max:255',
            'pass_year' => 'required|array|min:1',
            'pass_year.*' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'students');

        Student::create([
            'name' => $request->name,
            'photo_path' => $photoPath,
            'degree' => array_values(array_filter($request->degree)),
            'department' => array_values(array_filter($request->department)),
            'pass_year' => array_values(array_filter($request->pass_year)),
            'status' => 'pending',
            'source' => 'alumni_network',
        ]);

        return redirect()->back()->with('success', 'Your alumni network request has been submitted successfully and is pending admin approval.');
    }

    public function approve(Student $student)
    {
        $student->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Alumni request approved successfully.');
    }

    public function reject(Student $student)
    {
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();

        return redirect()->back()->with('success', 'Alumni request rejected and removed successfully.');
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
