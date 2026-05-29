<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\StudentDataStatus;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function attemptLogin(Request $request)
    {
        // Attempt login only if the user is approved
        return $this->guard()->attempt(
            $this->credentials($request) + ['approved' => true], $request->filled('remember')
        );
    }

    // Handle failed login attempt
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && !$user->approved) {
            // If user exists but is not approved, return a specific error message
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.not_approved')],
            ]);
        }

        // Default login failure response
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected $redirectTo = '/home';

    protected function authenticated(Request $request, $user)
    {
        if ($request->filled('login_modal')) {
            $request->session()->flash('login_success', 'Login successful. Welcome back.');
        }

        StudentDataStatus::ensureCompletionReminder($user);

        if (! $user->studentsData()->exists()) {
            return redirect()
                ->route('students_data.create')
                ->with('login_success', 'Login successful. Please complete your student data.')
                ->with('warning', 'Your passport, visa, and green card information is missing. Please update your student data.');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    
}
