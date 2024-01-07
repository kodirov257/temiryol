<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegistrationController extends BaseController
{
    private AuthenticationService $service;

    public function __construct(AuthenticationService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'email', 'min:8', 'max:50', 'unique:' . User::class],
                'password' => ['required', 'string', 'min:8', 'max:120', 'confirmed', Password::defaults()],
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
            ]);

            event(new Registered($user = $this->service->register(
                $request->name, $request->email, $request->password, null, $request->first_name, $request->last_name
            )));

            return $this->sendResponse(null, trans('adminlte.verify_email_sent'));
        } catch (ValidationException $e) {
            return $this->sendError(trans('auth.user_exists'), $e->errors(), $e->status);
        } catch (\DomainException|\Exception|\Throwable $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }
}
