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
    public function register(Request $request): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' =>$request->name,
                'email' => $request->email,
                'role' => User::ROLE_USER,
                'status' => User::STATUS_WAIT,
                'password' => Hash::make($request->password),
                'email_verify_token' => Str::uuid() . '_' . date('Y-m-d-H:i:s'),
            ]);

            $user->profile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
