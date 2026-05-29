<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Support\GoogleTwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminTwoFactorController extends Controller
{
    public function __construct(
        private readonly GoogleTwoFactorService $twoFactor,
    ) {
    }

    public function showChallenge(Request $request)
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        if (! $admin->hasTwoFactorEnabled()) {
            return redirect()->route('admin.two-factor.setup');
        }

        return view('admin.two_factor.challenge', [
            'admin' => $admin,
        ]);
    }

    public function verifyChallenge(Request $request)
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $secret = $this->twoFactor->decryptSecret($admin->two_factor_secret);
        $code = trim((string) $request->input('code'));

        $verified = $secret && $this->twoFactor->verifyCode($secret, $code);

        if (! $verified && str_contains($code, '-')) {
            $verified = $this->twoFactor->consumeRecoveryCode($admin, $code);
        }

        if (! $verified) {
            return back()->withErrors([
                'code' => 'The authentication code or recovery code is invalid.',
            ]);
        }

        return $this->completeLogin($request, $admin);
    }

    public function showSetup(Request $request)
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        if ($admin->hasTwoFactorEnabled()) {
            return redirect()->route('admin.two-factor.challenge');
        }

        $secret = $request->session()->get('admin_two_factor_setup_secret');

        if (! $secret) {
            $secret = $this->twoFactor->generateSecret();
            $request->session()->put('admin_two_factor_setup_secret', $secret);
        }

        $otpAuthUri = $this->twoFactor->buildOtpAuthUri($admin, $secret);

        return view('admin.two_factor.setup', [
            'admin' => $admin,
            'secret' => $secret,
            'otpAuthUri' => $otpAuthUri,
            'qrCodeUrl' => $this->twoFactor->buildQrCodeUrl($otpAuthUri),
        ]);
    }

    public function confirmSetup(Request $request)
    {
        $admin = $this->pendingAdmin($request);

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $secret = $request->session()->get('admin_two_factor_setup_secret');

        if (! $secret) {
            return redirect()->route('admin.two-factor.setup');
        }

        if (! $this->twoFactor->verifyCode($secret, (string) $request->input('code'))) {
            return back()->withErrors([
                'code' => 'The authenticator code is invalid. Please try again.',
            ]);
        }

        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();

        $admin->forceFill([
            'two_factor_secret' => $this->twoFactor->encryptSecret($secret),
            'two_factor_recovery_codes' => $this->twoFactor->hashRecoveryCodes($recoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('admin_two_factor_setup_secret');
        $request->session()->put('admin_two_factor_recovery_codes_plain', $recoveryCodes);

        return $this->completeLogin($request, $admin, 'Two-factor authentication has been enabled successfully.');
    }

    public function enableFromProfile(Request $request)
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $secret = $request->session()->get('admin_profile_two_factor_secret');

        if (! $secret) {
            return redirect()->route('admin.profile.edit')->withErrors([
                'two_factor' => 'A new setup secret was generated. Please try again.',
            ]);
        }

        if (! $this->twoFactor->verifyCode($secret, (string) $request->input('code'))) {
            return back()->withErrors([
                'code' => 'The authenticator code is invalid.',
            ]);
        }

        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();

        $admin->forceFill([
            'two_factor_secret' => $this->twoFactor->encryptSecret($secret),
            'two_factor_recovery_codes' => $this->twoFactor->hashRecoveryCodes($recoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('admin_profile_two_factor_secret');
        $request->session()->flash('admin_two_factor_recovery_codes_plain', $recoveryCodes);

        return redirect()->route('admin.profile.edit')->with('success', 'Google Authenticator 2FA has been enabled.');
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (! Hash::check((string) $request->input('current_password'), $admin->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        if (! $admin->hasTwoFactorEnabled()) {
            return back()->withErrors([
                'two_factor' => 'Two-factor authentication is not enabled yet.',
            ]);
        }

        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();
        $admin->two_factor_recovery_codes = $this->twoFactor->hashRecoveryCodes($recoveryCodes);
        $admin->save();

        $request->session()->flash('admin_two_factor_recovery_codes_plain', $recoveryCodes);

        return redirect()->route('admin.profile.edit')->with('success', 'Recovery codes have been regenerated.');
    }

    public function disable(Request $request)
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (! Hash::check((string) $request->input('current_password'), $admin->password)) {
            return back()->withErrors([
                'disable_current_password' => 'The current password is incorrect.',
            ]);
        }

        $admin->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget('admin_profile_two_factor_secret');
        $request->session()->forget('admin_two_factor_recovery_codes_plain');

        return redirect()->route('admin.profile.edit')->with('success', 'Two-factor authentication has been disabled.');
    }

    public function cancel(Request $request)
    {
        $request->session()->forget([
            'admin_two_factor_pending_id',
            'admin_two_factor_remember',
            'admin_two_factor_setup_secret',
        ]);

        return redirect()->route('admin.login');
    }

    private function completeLogin(Request $request, Admin $admin, ?string $message = null)
    {
        Auth::guard('admin')->login($admin, (bool) $request->session()->pull('admin_two_factor_remember', false));
        $request->session()->forget([
            'admin_two_factor_pending_id',
            'admin_two_factor_setup_secret',
        ]);
        $request->session()->regenerate();

        $redirect = redirect()->route('admin.dashboard');

        return $message ? $redirect->with('success', $message) : $redirect;
    }

    private function pendingAdmin(Request $request): ?Admin
    {
        $adminId = $request->session()->get('admin_two_factor_pending_id');

        if (! $adminId) {
            return null;
        }

        return Admin::find($adminId);
    }
}
