<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
    public function otpverify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User not found']);
        }

        $storedOtp = Cache::get('email_otp_' . $user->id);

        if (!$storedOtp || $request->otp != $storedOtp) {
            return redirect()->back()->withErrors(['error' => 'Invalid OTP']);
        }

        // Mark email as verified
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($request->user()));
        }
    
        // Remove OTP after successful verification
        Cache::forget('email_otp_' . $user->id);

        // Redirect to the dashboard
        return redirect()->route('dashboard')->with('success', 'Email verified successfully.');
    }

}
