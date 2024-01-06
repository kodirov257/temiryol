<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        session(['url.intended' => url()->previous()]);
        $this->middleware('guest')->except('logout');
    }

    public function loginForm(): View
    {
        try {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->previous()]);
            }

            return view('auth.login');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (ValidationException $e) {
            return redirect()->route('login')->withInput($request->only($request->username(), 'remember'))->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->route('login')->withInput($request->only($request->username(), 'remember'))->withErrors([
                $request->username() => trans('auth.failed'),
                'password' => trans('auth.wrong_password'),
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('dashboard.home');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
