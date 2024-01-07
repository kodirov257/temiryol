<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class RequestHelper
{
    public static function isApiRequest(): bool
    {
        return request()->wantsJson();
    }
}
