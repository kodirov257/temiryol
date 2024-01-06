<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    private AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'email', 'min:8', 'max:50', 'unique:' . User::class],
                'password' => ['required', 'string', 'min:8', 'max:120', 'confirmed', Password::defaults()],
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
            ]);

            $google2fa = app('pragmarx.google2fa');
            $google2faSecret = $google2fa->generateSecretKey();

            event(new Registered($user = $this->authService->register(
                $request->name, $request->email, $request->password, $google2faSecret, $request->first_name, $request->last_name
            )));

            Session::put('auth', ['email' => $user->email]);

            return $this->registered($request, $user);
        } catch (ValidationException $e) {
            return redirect()->route('register')->withInput($request->all())
                ->with('error', trans('auth.user_exists'))->withErrors($e->errors());
        } catch (\DomainException|\Exception|\Throwable $e) {
            return redirect('register')->with('error', $e->getMessage());
        }
    }

    public function completeRegistration(Request $request): RedirectResponse
    {
        if (!$request->email || $user = User::where('email', $request->email)) {
            return back()->with('error', trans('auth.email_not_identified'));
        }

        if (!$user->hasVerifiedEmail() || !$user->google2fa_secret) {
            return back()->with('error', trans('auth.email_not_verified'));
        }

        return redirect()->route('login')->with('success', trans('auth.email_verified_login'));
    }

    protected function registered(Request $request, User $user): RedirectResponse
    {
        return redirect()->route('verification.notice');
    }
}
