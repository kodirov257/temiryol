<?php

namespace App\Http\Requests\Auth;

use App\Helpers\UserHelper;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @property string $email_or_username
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    private string $login;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if (UserHelper::isEmail($this->email_or_username)) {
            $rule = ['email_or_username' => 'required|string|email|max:100'];
            $this->login = 'email';
        } else {
            $rule = ['email_or_username' => 'required|string|max:100'];
            $this->login = 'name';
        }

        return array_merge($rule, [
            'password' => 'required|string|max:120',
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(?string $guard = null): void
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::guard($guard)->attempt($this->getCredentials(), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $this->username() => trans('auth.failed'),
            ]);
        }

        $this->clearLoginAttempts();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->username() => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])
        ]);
    }

    public function authenticated(): RedirectResponse
    {
        if (Auth::guard()->user() && Auth::guard()->user()->isWait()) {
            Auth::guard()->logout();

            return back()->with('error', trans('auth.need_to_confirm_email'));
        }

        return redirect()->intended(AuthenticationService::getHomeRoutePath());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email_or_username')) . '|' . $this->ip());
    }

    public function getCredentials(): array
    {
        return [
            $this->login => $this->email_or_username,
            'password' => $this->password
        ];
    }

    public function username(): string
    {
        return 'email_or_username';
    }

    private function clearLoginAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}
