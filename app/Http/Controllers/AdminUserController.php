<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mpdf\Output\Destination;
use App\Models\UserCustomField;
use App\Models\UserFieldData;
use Illuminate\Support\Facades\Hash;
use App\Support\ImageCompressor;
use App\Support\UserEmailService;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $value = $request->input('value');

        // Filter users based on the selected category and value
        $users = User::where($category, $value)
            ->orderBy('room_number')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'category', 'value'));
    }



    // View user details
    public function view($id)
    {
        $user = User::with(['studentsData', 'emergencyContacts'])->find($id);
        if (!$user) {
            return redirect()->route('admin.dashboard')->with('error', 'User not found');
        }

        $submittedData = UserFieldData::where('user_id', $id)->with('customField')->get();

        return view('admin.users.view', compact('user', 'submittedData'));
    }

    
    public function downloadPDF($id)
    {
        // Fetch user data
        $user = User::with('studentsData')->find($id);
    
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
    
        // Get the path to the user's main photo
        $photoPath = !empty($user->photo) ? public_path('storage/' . $user->photo) : null;
    
        // Get paths to the additional photos (passport, visa, green card)
        $passportPhotoPath = !empty($user->studentsData->passport_photo) ? public_path('storage/' . $user->studentsData->passport_photo) : null;
        $visaPhotoPath = !empty($user->studentsData->visa_photo) ? public_path('storage/' . $user->studentsData->visa_photo) : null;
        $greenCardPhotoPath = !empty($user->studentsData->green_card_photo) ? public_path('storage/' . $user->studentsData->green_card_photo) : null;
    
        // Retrieve custom field data (including potential images)
        $submittedData = UserFieldData::where('user_id', $id)->with('customField')->get();
    
        // Check if all additional photos exist
        $allPhotosExist = file_exists($passportPhotoPath) && file_exists($visaPhotoPath) && file_exists($greenCardPhotoPath);
    
        // Load the view and generate HTML for PDF
        $html = view('admin.users.pdf', compact('user', 'submittedData', 'allPhotosExist'))->render();
    
        // Initialize Mpdf
        $mpdf = new Mpdf();
    
        // Store the user's main photo in MPDF imageVars if it exists
        if ($photoPath && file_exists($photoPath) && is_file($photoPath)) {
            $mpdf->imageVars['user_photo'] = file_get_contents($photoPath);
        }
    
        // If all three additional photos exist, add them to the PDF
        if ($allPhotosExist) {
            if ($passportPhotoPath && file_exists($passportPhotoPath) && is_file($passportPhotoPath)) {
                $mpdf->imageVars['passport_photo'] = file_get_contents($passportPhotoPath);
            }
            if ($visaPhotoPath && file_exists($visaPhotoPath) && is_file($visaPhotoPath)) {
                $mpdf->imageVars['visa_photo'] = file_get_contents($visaPhotoPath);
            }
            if ($greenCardPhotoPath && file_exists($greenCardPhotoPath) && is_file($greenCardPhotoPath)) {
                $mpdf->imageVars['green_card_photo'] = file_get_contents($greenCardPhotoPath);
            }
        }
    
        // Write HTML to the Mpdf document
        $mpdf->WriteHTML($html);
    
        // Set paper size and orientation (optional)
        $mpdf->SetDisplayMode('fullpage');
    
        // Output the generated PDF (force download)
        $mpdf->Output('user-details.pdf', 'D'); // 'D' for download
    }



    //User Edit
    public function edit($id)
    {
        $user = User::with('studentsData')->findOrFail($id);
        $duplicateUsers = User::with('studentsData')
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($user) {
                $userPassportNumber = trim((string) optional($user->studentsData)->passport_number);

                if (filled($user->full_name)) {
                    $query->orWhereRaw('LOWER(TRIM(full_name)) = ?', [mb_strtolower(trim((string) $user->full_name))]);
                }

                if (filled($userPassportNumber)) {
                    $query->orWhereHas('studentsData', function ($studentsDataQuery) use ($userPassportNumber) {
                        $studentsDataQuery->whereRaw('LOWER(TRIM(passport_number)) = ?', [mb_strtolower($userPassportNumber)]);
                    });
                }
            })
            ->get()
            ->map(function ($duplicateUser) use ($user) {
                $reasons = [];
                $userPassportNumber = mb_strtolower(trim((string) optional($user->studentsData)->passport_number));
                $duplicatePassportNumber = mb_strtolower(trim((string) optional($duplicateUser->studentsData)->passport_number));

                if (mb_strtolower(trim((string) $duplicateUser->full_name)) === mb_strtolower(trim((string) $user->full_name))) {
                    $reasons[] = 'Name';
                }

                if (filled($userPassportNumber) && $userPassportNumber === $duplicatePassportNumber) {
                    $reasons[] = 'Passport';
                }

                $duplicateUser->duplicate_match_reasons = $reasons;

                return $duplicateUser;
            })
            ->filter(fn ($duplicateUser) => ! empty($duplicateUser->duplicate_match_reasons))
            ->values();

        return view('admin.users.edit', compact('user', 'duplicateUsers'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'room_number' => 'required',
            'country' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'date_of_birth' => 'required|date',
            'course_type' => 'required',
            'department' => 'required',
            'other_department' => 'nullable',
            'course_year' => 'nullable',
            'course_language' => 'nullable',
            'passport_number' => 'nullable|string',
            'visa_start_date' => 'nullable|date',
            'visa_expiry_date' => 'nullable|date',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visa_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'green_card_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'room_number' => $request->room_number,
            'country' => $request->country,
            'address' => $request->address,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'date_of_birth' => $request->date_of_birth,
            'course_type' => $request->course_type,
            'department' => $request->department === 'Other' ? $request->other_department : $request->department,
            'course_year' => $request->course_year,
            'course_language' => $request->course_language,
            'passport_number' => $request->passport_number,
            'visa_start_date' => $request->visa_start_date,
            'visa_expiry_date' => $request->visa_expiry_date,
        ]);

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'photos');
            $user->photo = $photoPath;
        }

        if ($request->hasFile('passport_photo')) {
            $passportPhotoPath = ImageCompressor::storeUploadedFile($request->file('passport_photo'), 'documents');
            $user->passport_photo = $passportPhotoPath;
        }

        if ($request->hasFile('visa_photo')) {
            $visaPhotoPath = ImageCompressor::storeUploadedFile($request->file('visa_photo'), 'documents');
            $user->visa_photo = $visaPhotoPath;
        }

        if ($request->hasFile('green_card_photo')) {
            $greenCardPhotoPath = ImageCompressor::storeUploadedFile($request->file('green_card_photo'), 'documents');
            $user->green_card_photo = $greenCardPhotoPath;
        }

        $user->save();

        return redirect()->route('admin.users.view', $user->id)->with('success', 'User updated successfully');
    }


    // Delete user
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.dashboard')->with('error', 'User not found');
        }

        // Delete user
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }
    public function show($id)
    {


    }
    
    public function forgetPassword($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);
        $newPassword = '1234567890';

        // Set the new password
        $user->password = Hash::make($newPassword); // Securely hash the password
        $user->registration_password_plain = $newPassword;
        $user->save();
        UserEmailService::sendPasswordReset($user, $newPassword);

        // Redirect with success message
        return redirect()->back()->with('success', 'User password has been reset successfully and emailed to the user.');
    }
    
}
