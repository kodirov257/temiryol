<?php

namespace App\Services\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationService
{
    public static function getHomeRouteName(?User $user = null): string
    {
        if (!$user ? Auth::user()->isAdmin() : $user->isAdmin()) {
            return 'dashboard.home';
        }

        return 'home';
    }

    public static function getHomeRoutePath(?User $user = null): string
    {
        $route = self::getHomeRouteName($user);

        session(['url.intended' => route($route)]);

        return route($route);
    }

    /**
     * @throws \Throwable
     */
    public function register(string $name, string $email, string $password, string $google2faSecret, ?string $firstName = null, ?string $lastName = null): User
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
}
