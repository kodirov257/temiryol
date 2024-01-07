<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function info(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user());
    }
}
