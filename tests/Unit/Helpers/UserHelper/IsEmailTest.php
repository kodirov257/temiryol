<?php

namespace Tests\Unit\Helpers\UserHelper;

use App\Helpers\UserHelper;
use Tests\TestCase;

class IsEmailTest extends TestCase
{
    public function test_given_email_is_email(): void
    {
        $result = UserHelper::isEmail('test@example.com');

        self::assertTrue($result);
    }

    public function test_given_email_is_not_email(): void
    {
        $result = UserHelper::isEmail('wrong-email');

        self::assertFalse($result);
    }
}
