<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Str;
use App\Support\ImageCompressor;


class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user(); // Get the currently authenticated admin
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user(); // Get the currently authenticated admin

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'admin_photos');
            $admin->photo = $photoPath;
        }

        $admin->save(); // Save the updated admin details to the database

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
    
    public function resetPassword($id)
    {
        $admin = Admin::findOrFail($id);

        // Set the password to '1234567890' and hash it
        $newPassword = '1234567890'; // Static password
        $admin->password = bcrypt($newPassword); // Hash the password before saving it
        $admin->save();

        // Optionally send email notification
        // Mail::to($admin->email)->send(new AdminPasswordResetMail($admin, $newPassword));

        return back()->with('success', 'Password reset successfully! New password: ' . $newPassword);
    }

}
