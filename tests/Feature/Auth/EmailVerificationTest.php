<?php

namespace Feature\Auth;

use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Google2FA;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->withSession(['auth' => ['email' => $user->email]])->get('/verify-email');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    public function test_email_can_be_verified_when_admin(): void
    {
        $user = User::factory()->unverified()->create([
            'role' => User::ROLE_ADMIN,
            'google2fa_secret' => Google2FA::generateSecretKey(),
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.email.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => $user->email_verify_token]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        self::assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertViewIs('auth.google2fa.register');
    }

    public function test_email_can_be_verified_when_user(): void
    {
        $user = User::factory()->unverified()->create([
            'google2fa_secret' => Google2FA::generateSecretKey(),
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.email.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => $user->email_verify_token]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        self::assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/login');
    }

    public function test_email_is_already_verified(): void
    {
        $user = User::factory()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.email.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => Str::uuid() . '_' . date('Y-m-d-H:i:s')]
        );

        $response = $this->get($verificationUrl);

        Event::assertNotDispatched(Verified::class);
        self::assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/login');
    }
}
