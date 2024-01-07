<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends BaseController
{
    public function sendResetByEmail(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? $this->sendResponse(result: null, message: __($status))
                : $this->sendError(__($status));
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), $e->status);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    public function resetByEmail(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? $this->sendResponse(result: null, message: __($status))
                : $this->sendError(__($status));
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), $e->status);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }
}
