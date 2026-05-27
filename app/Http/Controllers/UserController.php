<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Support\UserProfileEditSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected function adminUserCategoryQuery(string $category, $value = null)
    {
        $query = User::approved();

        switch ($category) {
            case 'total':
                return $query;
            case 'nationality':
            case 'country':
                return $query->where('country', $value);
            case 'religion':
                return $query->where('religion', $value);
            case 'department':
                return $query->where('department', $value);
            case 'course':
                return $query->where('course', $value);
            default:
                return User::query()->whereRaw('1 = 0');
        }
    }

    public function edit()
    {
        $user = Auth::user()->load('emergencyContacts'); // Get the currently authenticated user
        $editableFields = UserProfileEditSettings::normalizeEditableFields(
            WebsiteSetting::query()->first()?->user_editable_fields
        );
        $fieldDefinitions = UserProfileEditSettings::fields();

        return view('user.edit', compact('user', 'editableFields', 'fieldDefinitions')); // Return a view with the user data
    }

    
        // Show the user details
    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch user by ID
    
        return view('admin.users.show', compact('user'));
    }
    

    public function update(Request $request)
    {
        $formAction = $request->input('form_action', 'profile');
        $editableFields = UserProfileEditSettings::normalizeEditableFields(
            WebsiteSetting::query()->first()?->user_editable_fields
        );

        if ($formAction === 'password') {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
        } else {
            $rules = [];

            if (in_array('email', $editableFields, true)) {
                $rules['email'] = 'required|email|unique:users,email,' . Auth::id();
            }

            foreach (['mobile_number', 'course_type', 'department', 'course_year', 'course_language', 'room_number'] as $field) {
                if (in_array($field, $editableFields, true)) {
                    $rules[$field] = 'nullable';
                }
            }

            $rules['emergency_contacts'] = 'nullable|array';
            $rules['emergency_contacts.*.platform'] = 'nullable|string|max:100';
            $rules['emergency_contacts.*.contact_value'] = 'nullable|string|max:255';

            $request->validate($rules);
        }

        // Get the currently authenticated user
        $user = Auth::user();

        if ($formAction === 'password') {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()
                    ->route('user.edit')
                    ->withErrors(['current_password' => 'Old password is incorrect.'])
                    ->withInput();
            }

            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('user.edit')->with('success', 'Password updated successfully.');
        }

        // Update only the fields that the user is allowed to edit
        foreach ($editableFields as $field) {
            $user->{$field} = $request->input($field);
        }

        // Save the updated user information
        $user->save();

        $emergencyContacts = collect($request->input('emergency_contacts', []))
            ->map(function ($contact) {
                return [
                    'platform' => trim((string) ($contact['platform'] ?? '')),
                    'contact_value' => trim((string) ($contact['contact_value'] ?? '')),
                ];
            })
            ->filter(fn ($contact) => $contact['platform'] !== '' || $contact['contact_value'] !== '')
            ->filter(fn ($contact) => $contact['platform'] !== '' && $contact['contact_value'] !== '')
            ->values();

        $user->emergencyContacts()->delete();

        if ($emergencyContacts->isNotEmpty()) {
            $user->emergencyContacts()->createMany($emergencyContacts->all());
        }

        return redirect()->route('user.edit')->with('success', 'Profile updated successfully.');
    }
    
    
    public function listByCategory($category, $value = null)
    {
        $search = trim((string) request('search'));
        $query = $this->adminUserCategoryQuery($category, $value);

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $like = '%' . $search . '%';

                $builder->where('full_name', 'like', $like)
                    ->orWhere('room_number', 'like', $like)
                    ->orWhere('mobile_number', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('country', 'like', $like)
                    ->orWhere('department', 'like', $like);
            });
        }

        $users = $query->orderBy('room_number')->paginate(20)->withQueryString();

        if (request()->ajax()) {
            return response()->json([
                'cards' => view('admin.partials.user_details_cards', compact('users'))->render(),
                'pagination' => $users->links()->render(),
                'count' => $users->total(),
            ]);
        }

        return view('admin.user_details', compact('users', 'category', 'value', 'search'));
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

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
