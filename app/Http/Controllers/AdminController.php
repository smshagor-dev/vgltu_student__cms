<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;


use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Support\ImageCompressor;
use App\Support\UserEmailService;

class AdminController extends Controller
{
    public function pendingUsers()
    {
        $pendingUsers = User::where('approved', false)
            ->orderBy('room_number')
            ->paginate(20);
        return view('admin.pending_users', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->approved = true;
            $user->save();
            UserEmailService::sendApproval($user);

            return redirect()->back()->with('success', 'User has been approved successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    public function rejectUser($id)
    {
        $user = User::find($id);
        if ($user) {
            UserEmailService::sendRejection($user);
            $user->delete();

            return redirect()->back()->with('success', 'User has been rejected and deleted.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    // Admin Create

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'photos');
            $admin->photo = $photoPath;
        }

        $admin->save();

        return redirect()->route('admin.create')->with('success', 'Admin created successfully.');
    }

    // Show list of admins
    public function index()
    {
        $admins = Admin::query()
            ->latest()
            ->paginate(20);

        return view('admin.index', compact('admins'));
    }

    // Delete admin
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id); // Find admin by ID
        if ($admin->photo && file_exists(public_path('storage/' . $admin->photo))) {
            unlink(public_path('storage/' . $admin->photo)); // Delete photo if it exists
        }
        $admin->delete(); // Delete admin record
        return redirect()->route('admin.profile.index')->with('success', 'Admin deleted successfully.');
    }

    // Admin show by id
    
    public function show($id)
    {
        $admin = Admin::findOrFail($id); // Retrieve the admin or fail with a 404 error
        return view('admin.profile.show', compact('admin'));
    }
}
