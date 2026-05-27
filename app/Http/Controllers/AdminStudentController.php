<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsData;
use App\Support\ImageCompressor;

class AdminStudentController extends Controller
{
    // View all students
    public function index()
    {
        $students = StudentsData::query()
            ->with('user')
            ->latest()
            ->paginate(20);
        return view('admin.student_data.index', compact('students'));
    }

    // Show edit form
    public function edit($id)
    {
        $student = StudentsData::where('id', $id)->first();
        if (!$student) {
            return redirect()->route('admin.students.index')->with('error', 'Student not found');
        }
        return view('admin.student_data.edit', compact('student'));
    }

    // Update student data
    public function update(Request $request, $id)
    {
        $request->validate([
            'passport_number' => 'required|string|max:255',
            'visa_start_date' => 'required|date',
            'visa_expiry_date' => 'required|date',
            'passport_photo' => 'image|mimes:jpeg,png,jpg|max:20048',
            'visa_photo' => 'image|mimes:jpeg,png,jpg|max:20048',
            'green_card_photo' => 'image|mimes:jpeg,png,jpg|max:20048',
        ]);

        $student = StudentsData::findOrFail($id);
        $student->passport_number = $request->passport_number;
        $student->visa_start_date = $request->visa_start_date;
        $student->visa_expiry_date = $request->visa_expiry_date;

        // Handle photo uploads
        if ($request->hasFile('passport_photo')) {
            $student->passport_photo = ImageCompressor::storeUploadedFile($request->file('passport_photo'), 'photos');
        }
        if ($request->hasFile('visa_photo')) {
            $student->visa_photo = ImageCompressor::storeUploadedFile($request->file('visa_photo'), 'photos');
        }
        if ($request->hasFile('green_card_photo')) {
            $student->green_card_photo = ImageCompressor::storeUploadedFile($request->file('green_card_photo'), 'photos');
        }

        $student->save();

        return redirect()->route('admin.studentsdata.index')->with('success', 'Student data updated successfully.');
    }

    // Delete student data
    public function destroy($id)
    {
        $student = StudentsData::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.studentsdata.index')->with('success', 'Student data deleted successfully.');
    }
}
