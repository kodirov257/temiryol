<?php

namespace Tests\Unit\Services\AuthenticationService;

use App\Services\Auth\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_successfully(): void
    {
        $service = $this->app->make(AuthenticationService::class);

        $user = $service->register(
            name: $username = 'TestUser',
            email: $email = 'test@example.com',
            password: $password = 'password',
            firstName: $firstName = 'Test',
            lastName: $lastName = 'User'
        );

        self::assertSame($username, $user->name);
        self::assertSame($email, $user->email);
        self::assertTrue(Hash::check($password, $user->password));
        self::assertSame($firstName, $user->profile->first_name);
        self::assertSame($lastName, $user->profile->last_name);
    }
}
