<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{
    private AuthenticationService $service;

    public function __construct(AuthenticationService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->service->authenticate($request);

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken($user->name)->plainTextToken,
            ]);
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), $e->status);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [
                $request->username() => trans('auth.failed'),
                'password' => trans('auth.wrong_password'),
            ], $e->getCode());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $request->user()->currentAccessToken()->delete();

            Auth::guard('web')->logout();

            return $this->sendResponse($user);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }
}
