<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Support\ImageCompressor;
use App\Support\UserEmailService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function registered(Request $request, $user)
    {
        // After registration, show a message that the account is pending approval
        return redirect('/login')->with('warning', 'Your account is pending approval by the admin.');
    }
    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'mobile_number' => ['required', 'string', 'max:15'],
            'room_number' => ['required', 'string', 'max:10'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validate the photo
            'country' => ['required', 'in:Bangladesh,India,Nepal'],
            'address' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'in:Muslim,Hindu,Boddho,Cristan'],
            'gender' => ['required', 'in:Male,Female'],
            'date_of_birth' => ['required', 'date'],
            'course_type' => ['required', 'in:Language,BSC,MSC,PHD'],
            'department' => ['required', 'in:Prepetory Language Course,Automobile,Forestry,Mechanical,Computer Science and Technology,Economics,automation of production processes,Life Safety and Legal Relations,
                                Botany and Plant Physiology, Hardware and Software,  Landscape Architecture, Tourism'],
            'course_year' => ['nullable', 'in:1st Year,2nd Year,3rd Year,Final Year'],
            // Existing validations...
            'course_language' => ['nullable', 'in:English,Russian'],
            // 'department' => ['required', 'string', 'max:255'],
            'other_department' => ['nullable', 'string', 'max:255'], // Optional if not selected
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate incoming request data
        $this->validator($request->all())->validate();
        
        // Create the user with the pending approval status
        event(new Registered($user = $this->create($request)));
        UserEmailService::sendRegistrationPending($user, (string) $request->password);

        // Create the user with the pending approval status
         session()->flash('message', 'Your registration is pending approval. Please wait for admin approval.');

        // Redirect to login page with a message about pending approval
        return redirect()->route('login')->with('warning', 'Your account is pending approval by the admin.');
    }      

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create($request)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);


        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile($request->file('photo'), 'photos');
        }
        
        $user = new user();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->registration_password_plain = $request->password;
        $user->mobile_number = $request->mobile_number;
        $user->room_number = $request->room_number;
        $user->photo = $photoPath;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->religion = $request->religion;
        $user->date_of_birth = $request->date_of_birth;
        $user->course_type = $request->course_type;
        $user->department = $request->department === 'Other' ? $request->other_department : $request->department;
        $user->course_year = $request->course_year;
        $user->course_language = $request->course_language;
        $user->gender = $request->gender;
        $user->save();
        return $user;
    }
    

       
}
