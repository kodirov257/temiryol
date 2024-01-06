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
    public function verifyForm(Request $request): RedirectResponse|View
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
                ? redirect()->intended(AuthenticationService::getHomeRoute())
                : view('auth.verify', compact('user'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', trans('auth.email_verified_login'));
        }

        if ($request->user->markEmailAsVerified()) {
            event(new Verified($request->user));
        }

        return redirect()->route('login')->with('success', trans('auth.email_verified_login'));
    }

    public function sendEmailVerificationNotification(Request $request): RedirectResponse
    {
        try {
            $request->validate(['email' => ['required', 'string', 'email', 'min:8', 'max:50']]);

            if (!$user = User::where('email', $request->email)->first()) {
                return redirect()->back()->with('error', trans('auth.email_not_found'));
            }

            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(AuthenticationService::getHomeRoute() . '?verified=1');
            }

            $user->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
