<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends BaseController
{
    public function verifyByEmail(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => ['required', 'integer', 'min:1'],
                'hash' => ['nullable', 'string'],
            ]);

            $user = User::findOrFail($request->id);

            if ($user->hasVerifiedEmail()) {
                return $this->sendResponse(null, trans('auth.email_verified_login'));
            }

            if (! hash_equals($user->email_verify_token, (string) $request->hash)) {
                throw new \RuntimeException(trans('auth.incorrect_verify_token'), 422);
            }

            $emailVerifyToken = explode('_', $user->email_verify_token);
            $emailVerifyTokenTime = Carbon::createFromFormat('Y-m-d-H:i:s', end($emailVerifyToken));
            $emailVerifyTokenTime->addDays(30);
            if (!$emailVerifyTokenTime->gt(Carbon::now())) {
                throw new \RuntimeException(trans('auth.token_expired'), 422);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return $this->sendResponse(null, trans('auth.email_verified_login'));
        } catch (ValidationException $e) {
            return $this->sendError(trans('auth.user_not_found'), $e->errors(), $e->status);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(trans('auth.user_not_found'), [], 401);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    public function sendEmailVerificationNotification(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email', 'min:8', 'max:50'],
            ]);

            $user = User::where('email', $request->email)->firstOrFail();

            if ($user->hasVerifiedEmail()) {
                return $this->sendResponse(null, trans('auth.email_verified_login'));
            }

            $user->sendEmailVerificationNotification();

            return $this->sendResponse(null, 'verification-link-sent');
        } catch (ValidationException $e) {
            return $this->sendError(trans('auth.email_not_identified'), $e->errors(), $e->status);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(trans('auth.email_not_found'), [], 401);
        }
    }
}
