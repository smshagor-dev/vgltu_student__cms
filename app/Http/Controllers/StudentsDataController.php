<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsData;
use Illuminate\Support\Facades\Auth;
use App\Support\ImageCompressor;

class StudentsDataController extends Controller
{
    public function index()
    {
        // Fetch only the logged-in user's details
        $students = \DB::table('students_data')
            ->join('users', 'students_data.user_id', '=', 'users.id')
            ->select('students_data.*', 'users.full_name') // Get all student data along with full_name
            ->where('students_data.user_id', auth()->id()) // Filter by logged-in user
            ->get();
    
        return view('students_data.index', compact('students'));
    }




    public function create()
    {
        return view('students_data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'passport_number' => 'required|string|max:50',
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
            'visa_start_date' => 'required|date',
            'visa_expiry_date' => 'required|date|after_or_equal:visa_start_date',
            'visa_photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
            'green_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        // Save uploaded files
        $passportPhotoPath = ImageCompressor::storeUploadedFile($request->file('passport_photo'), 'uploads/passport_photos');
        $visaPhotoPath = ImageCompressor::storeUploadedFile($request->file('visa_photo'), 'uploads/visa_photos');
        $greenCardPhotoPath = ImageCompressor::storeUploadedFile($request->file('green_card_photo'), 'uploads/green_card_photos');

        StudentsData::create([
            'user_id' => auth()->user()->id,
            'passport_number' => $request->passport_number,
            'passport_photo' => $passportPhotoPath,
            'visa_start_date' => $request->visa_start_date,
            'visa_expiry_date' => $request->visa_expiry_date,
            'visa_photo' => $visaPhotoPath,
            'green_card_photo' => $greenCardPhotoPath,
        ]);

        return redirect()->route('students_data.index')->with('success', 'Student data saved successfully.');
    }


    public function edit($id)
    {
        $studentData = StudentsData::findOrFail($id);
        return view('students_data.edit', compact('studentData'));
    }


    public function update(Request $request, $id)
    {
        $studentData = StudentsData::findOrFail($id);

        if (! $request->filled('visa_start_date') && $studentData->visa_start_date) {
            $request->merge([
                'visa_start_date' => $studentData->visa_start_date->format('Y-m-d'),
            ]);
        }

        $request->validate([
            'passport_number' => 'nullable|string|max:50',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
            'visa_start_date' => 'nullable|date',
            'visa_expiry_date' => 'nullable|date|after_or_equal:visa_start_date',
            'visa_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
            'green_card_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        // Ensure the user can only update their own data
        if (auth()->id() != $studentData->user_id) {
            return redirect()->route('students_data.index')->with('error', 'Unauthorized access!');
        }

        $originalVisaExpiryDate = optional($studentData->visa_expiry_date)?->toDateString();
        $updates = [];

        if ($request->filled('passport_number')) {
            $updates['passport_number'] = $request->passport_number;
        }

        if ($request->filled('visa_start_date')) {
            $updates['visa_start_date'] = $request->visa_start_date;
        }

        if ($request->filled('visa_expiry_date')) {
            $updates['visa_expiry_date'] = $request->visa_expiry_date;
        }

        $newVisaExpiryDate = $updates['visa_expiry_date'] ?? $originalVisaExpiryDate;

        if ($originalVisaExpiryDate !== $newVisaExpiryDate) {
            $updates['visa_reminder_90_sent_at'] = null;
            $updates['visa_reminder_75_sent_at'] = null;
            $updates['visa_reminder_60_sent_at'] = null;
            $updates['visa_overdue_10_sent_at'] = null;
        }

        if ($request->hasFile('passport_photo')) {
            $updates['passport_photo'] = ImageCompressor::storeUploadedFile($request->file('passport_photo'), 'uploads/passport_photos');
        }

        if ($request->hasFile('visa_photo')) {
            $updates['visa_photo'] = ImageCompressor::storeUploadedFile($request->file('visa_photo'), 'uploads/visa_photos');
        }

        if ($request->hasFile('green_card_photo')) {
            $updates['green_card_photo'] = ImageCompressor::storeUploadedFile($request->file('green_card_photo'), 'uploads/green_card_photos');
        }

        if ($updates === []) {
            return redirect()->route('students_data.edit', $studentData->id)->with('error', 'Please update at least one field before saving.');
        }

        $studentData->update($updates);

        return redirect()->route('students_data.index')->with('success', 'Student document details updated successfully.');
    }


}
