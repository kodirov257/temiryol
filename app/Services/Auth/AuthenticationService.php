<?php

namespace App\Services\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    public static function getHomeRoute(): string
    {
        if (Auth::user()->isAdmin()) {
            session(['url.intended' => route('dashboard.home')]);
            return route('dashboard.home');
        }

        if (Auth::user()->isUser()) {
            session(['url.intended' => route('home')]);
            return route('home');
        }

        return RouteServiceProvider::HOME;
    }
}
