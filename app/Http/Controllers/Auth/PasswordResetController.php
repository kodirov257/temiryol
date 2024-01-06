<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showEmail(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetByEmail(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
        } catch (ValidationException $e) {
            return back()->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function showResetByEmail(Request $request): View
    {
        return view('auth.passwords.reset', compact('request'));
    }

    public function resetByEmail(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'token' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
        } catch (ValidationException $e) {
            return back()->withInput($request->all())->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
