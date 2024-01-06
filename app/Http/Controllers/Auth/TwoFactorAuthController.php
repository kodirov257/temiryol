<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.google2fa.create-form');
    }

    public function store(Request $request): View|RedirectResponse
    {
        try {
            $request->validate(['email' => ['required', 'string', 'email', 'min:8', 'max:50']]);

            $user = User::where('email', $request->email)->firstOrFail();

            $google2fa = app('pragmarx.google2fa');
            $google2faSecret = $google2fa->generateSecretKey();

            $user->fill(['google2fa_secret' => $google2faSecret])->update();

            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $google2faSecret
            );

            return view('auth.google2fa.setup', ['QR_Image' => $QR_Image, 'secret' => $google2faSecret, 'email' => $user->email]);
        } catch (ValidationException $e) {
            return back()->withInput($request->all())
                ->with('error', trans('auth.email_not_identified'))->withErrors($e->errors());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', trans('auth.email_not_identified'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function complete(Request $request): RedirectResponse
    {
        if (!$request->email || !$user = User::where('email', $request->email)->first()) {
            return back()->with('error', trans('auth.email_not_identified'));
        }

        if (!$user->google2fa_secret) {
            return back()->with('error', trans('auth.email_not_verified'));
        }

        return redirect()->route('login')->with('success', trans('auth.email_verified_login'));
    }

}
