<?php

namespace App\Services\Auth;

use App\Helpers\RequestHelper;
use App\Helpers\UserHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    private Request|LoginRequest $request;

    public static function getHomeRouteName(?User $user = null): string
    {
        if (!$user ? Auth::user() && Auth::user()->isAdmin() : $user->isAdmin()) {
            return 'dashboard.home';
        }

        return 'home';
    }

    public static function getHomeRoutePath(?User $user = null): string
    {
        $route = self::getHomeRouteName($user);

        if (RequestHelper::isApiRequest()) {
            return config('app.front_url') . '/login';
        }

        session(['url.intended' => route($route)]);

        return route($route);
    }

    public static function getLoginRoutePath(): string
    {
        if (RequestHelper::isApiRequest()) {
            return config('app.front_url') . '/login';
        }

        return route('login');
    }

    /**
     * @throws \Throwable
     */
    public function register(string $name, string $email, string $password, ?string $google2faSecret, ?string $firstName = null, ?string $lastName = null): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' =>$name,
                'email' => $email,
                'role' => User::ROLE_USER,
                'status' => User::STATUS_WAIT,
                'password' => Hash::make($password),
                'email_verify_token' => Str::uuid() . '_' . date('Y-m-d-H:i:s'),
                'google2fa_secret' => $google2faSecret,
            ]);

            $user->profile()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request, ?string $guard = null): User
    {
        $this->request = $request;
        $this->ensureIsNotRateLimited();

        if ($request->boolean('remember')) {
            $attempt = Auth::guard($guard)->attempt($this->getCredentials($request), $request->boolean('remember'));
        } else {
            $attempt = Auth::guard($guard)->attempt($this->getCredentials($request));
        }
        if (!$attempt) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $this->request->username() => trans('auth.failed'),
            ]);
        }

        $this->clearLoginAttempts();

        return Auth::user();
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

        event(new Lockout($this->request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->request->username() => trans('auth.throttle', [
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

        return redirect()->intended(self::getHomeRoutePath());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->request->input($this->request->username())) . '|' . $this->request->ip());
    }

    public function getCredentials(Request $request): array
    {
        return [
            $this->loginName($request[$this->request->username()]) => $request[$this->request->username()],
            'password' => $request->password,
        ];
    }

    private function loginName(string $loginName): string
    {
        if (UserHelper::isEmail($loginName)) {
            return 'email';
        }

        return 'name';
    }

    private function clearLoginAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}
