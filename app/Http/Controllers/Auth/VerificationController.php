<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function verifyEmailForm(Request $request): RedirectResponse|View
    {
        try {
            $session = Session::get('auth');
            if (!$session || !$email = $session['email']) {
                return redirect()->route('register')->with('error', trans('auth.email_not_found'));
            }

            if (!$user = User::where('email', $email)->first()) {
                return redirect()->route('register')->with('error', trans('auth.email_not_found'));
            }

            return $user->hasVerifiedEmail()
                ? redirect()->intended(AuthenticationService::getHomeRoutePath())
                : view('auth.verify', compact('user'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse|View
    {
        if ($request->user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', trans('auth.email_verified_login'));
        }

        if ($request->user->markEmailAsVerified()) {
            event(new Verified($request->user));
        }

        $google2fa = app('pragmarx.google2fa');
        $secret = $request->user->google2fa_secret;
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $request->user->email,
            $secret
        );

        return view('auth.google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $secret, 'email' => $request->user->email]);
    }

    public function sendEmailVerificationNotification(Request $request): RedirectResponse
    {
        try {
            $request->validate(['email' => ['required', 'string', 'email', 'min:8', 'max:50']]);

            if (!$user = User::where('email', $request->email)->first()) {
                return redirect()->back()->with('error', trans('auth.email_not_found'));
            }

            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(AuthenticationService::getHomeRoutePath() . '?verified=1');
            }

            $user->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
