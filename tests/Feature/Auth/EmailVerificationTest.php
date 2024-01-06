<?php

namespace Feature\Auth;

use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->withSession(['auth' => ['email' => $user->email]])->get('/verify-email');
//        dd($response);

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => $user->email_verify_token]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        self::assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/login');
    }
}
