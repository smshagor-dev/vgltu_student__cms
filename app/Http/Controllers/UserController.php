<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user(); // Get the currently authenticated user

        return view('user.edit', compact('user')); // Return a view with the user data
    }

    
        // Show the user details
    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch user by ID
    
        return view('admin.users.show', compact('user'));
    }
    

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
        'email' => 'required|email|unique:users,email,' . Auth::id(), 
        'password' => 'nullable|min:8', 
        'mobile_number' => 'nullable',  
        'course_type' => 'nullable',    
        'department' => 'nullable',     
        'course_year' => 'nullable',
        'course_language' => 'nullable',
        'room_number' => 'nullable',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Update only the fields that the user is allowed to edit
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password); // Only update password if provided
        }

        $user->mobile_number = $request->mobile_number;
        $user->course_type = $request->course_type;
        $user->department = $request->department;
        $user->course_year = $request->course_year;
        $user->course_language = $request->course_language;
        $user->room_number = $request->room_number;

        // Save the updated user information
        $user->save();

        return redirect()->route('home')->with('success', 'Your Data Successfully Updated!');
    }
    
    
    public function listByCategory($category, $value = null)
    {
        switch ($category) {
            case 'total':
                $users = User::query()->orderBy('room_number')->paginate(20);
                break;
            case 'nationality':
                $users = User::where('country', $value)->orderBy('room_number')->paginate(20);
                break;
            case 'religion':
                $users = User::where('religion', $value)->orderBy('room_number')->paginate(20);
                break;
            case 'department':
                $users = User::where('department', $value)->orderBy('room_number')->paginate(20);
                break;
            case 'course':
                $users = User::where('course', $value)->orderBy('room_number')->paginate(20);
                break;
            default:
                $users = User::query()->whereRaw('1 = 0')->paginate(20);
        }

        return view('admin.user_details', compact('users'));
    }
    
    // User Delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Optional: Delete the user's photo from storage
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }
}
