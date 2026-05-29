<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Support\GoogleTwoFactorService;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function __construct(
        private readonly GoogleTwoFactorService $twoFactor,
    ) {
    }

    public function edit()
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $setupSecret = null;
        $setupQrCodeUrl = null;
        $setupOtpAuthUri = null;

        if (! $admin->hasTwoFactorEnabled()) {
            $setupSecret = session('admin_profile_two_factor_secret');

            if (! $setupSecret) {
                $setupSecret = $this->twoFactor->generateSecret();
                session(['admin_profile_two_factor_secret' => $setupSecret]);
            }

            $setupOtpAuthUri = $this->twoFactor->buildOtpAuthUri($admin, $setupSecret);
            $setupQrCodeUrl = $this->twoFactor->buildQrCodeUrl($setupOtpAuthUri);
        }

        return view('admin.profile.edit', [
            'admin' => $admin,
            'setupSecret' => $setupSecret,
            'setupQrCodeUrl' => $setupQrCodeUrl,
            'setupOtpAuthUri' => $setupOtpAuthUri,
            'plainRecoveryCodes' => session('admin_two_factor_recovery_codes_plain', []),
        ]);
    }

    public function update(Request $request)
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

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
