<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

            event(new Registered($user = $this->authService->register($request)));

            return $this->registered($request, $user);
        } catch (ValidationException $e) {
            return redirect()->route('register')->withInput($request->all())
                ->with('error', trans('auth.user_exists'))->withErrors($e->errors());
        } catch (\DomainException|\Exception|\Throwable $e) {
            return redirect('register')->with('error', $e->getMessage());
        }
    }

    protected function registered(Request $request, User $user): RedirectResponse
    {
        return redirect()->route('verification.notice');
    }
}
