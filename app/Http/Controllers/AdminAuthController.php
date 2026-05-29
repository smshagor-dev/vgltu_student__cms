<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        })->only(['showLoginForm', 'login']);
    }
    
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable',
        ]);

        /** @var Admin|null $admin */
        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check((string) $request->password, $admin->password)) {
            return back()
                ->withErrors(['email' => 'The provided admin credentials are incorrect.'])
                ->withInput($request->only('email'));
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('admin_two_factor_pending_id', $admin->id);
        $request->session()->put('admin_two_factor_remember', $request->boolean('remember'));

        if ($admin->hasTwoFactorEnabled()) {
            return redirect()->route('admin.two-factor.challenge');
        }

        return redirect()->route('admin.two-factor.setup')->with('status', 'Set up Google Authenticator to finish signing in.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
