<?php

namespace App\Helpers;

class UserHelper
{
    public static function isEmail(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }
}
