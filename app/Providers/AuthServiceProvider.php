<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Helpers\RequestHelper;
use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            if (!RequestHelper::isApiRequest()) {
                $route = route('password.email.reset.show', ['token' => $token]);
            } else {
                $route = sprintf('%s/forgot-password-email/%s', config('app.front_url'), $token);
            }
            return $route . '?email=' . $user->email;
        });

        VerifyEmail::createUrlUsing(function (User $user) {
            if (!RequestHelper::isApiRequest()) {
                return URL::temporarySignedRoute(
                    'verification.email.verify',
                    Carbon::now()->addMinutes(Config::get('auth.verification.expire', 1440)),
                    [
                        'id' => $user->getKey(),
                        'hash' => $user->email_verify_token,
                    ]
                );
            }

            return sprintf(
                '%s/verify-email/%s/%s',
                config('app.front_url'),
                $user->getKey(),
                $user->email_verify_token
            );
        });

        $this->registerPolicies();

        Gate::define('admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-regions', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-organizations', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-departments', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-instruments', function (User $user) {
            return $user->isAdmin();
        });
    }
}
