<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;
    
    public function showLoginForm()
    {
        return view('admin.login');
    }


     /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if the logged-in user is an admin (using the admin guard)
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
    
        // If the logged-in user is not an admin, log them out and redirect to login page
        Auth::logout();  // Logs out the non-admin user
        return redirect()->route('login')->with('error', 'You do not have permission to access the admin dashboard.');
    }



    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('admin.login');
    }

   /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard("admin");
    }

   
}
