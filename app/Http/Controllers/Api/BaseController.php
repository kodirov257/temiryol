<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function sendResponse(mixed $result, ?string $message = null): JsonResponse
    {
        return ApiResponseHelper::sendResponse($result, $message);
    }

    protected function sendError(string $error, $errorMessages = [], $code = 404): JsonResponse
    {
        return ApiResponseHelper::sendError($error, $errorMessages, $code);
    }
}
