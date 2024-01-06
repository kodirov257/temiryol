<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_authentication_screen_can_be_rendered(): void
    {
        $response = $this->get('/two-factor-auth');

        $response->assertStatus(200);
        $response->assertViewIs('auth.google2fa.create-form');
    }

    public function test_two_factor_authentication_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/two-factor-auth', [
            'email' => $user->email,
        ]);

        self::assertNotEmpty($user->fresh()->google2fa_secret);
        $response->assertViewIs('auth.google2fa.setup');
    }

    public function test_two_factor_authentication_store_error_not_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/two-factor-auth', [
            'email' => 'not-email',
        ]);

        $response->assertSessionHasErrors();
        $response->assertSessionHas('error', 'Sorry your email cannot be identified.');
    }

    public function test_two_factor_authentication_store_error_wrong_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/two-factor-auth', [
            'email' => 'wrong-email',
        ]);

        $response->assertSessionHasErrors();
        $response->assertSessionHas('error', 'Sorry your email cannot be identified.');
    }

    public function test_two_factor_authentication_is_completed_successfully(): void
    {
        $user = User::factory()->create();

        $this->post('/two-factor-auth', [
            'email' => $user->email,
        ]);

        $response = $this->post('/two-factor-auth/complete', [
            'email' => $user->email,
        ]);

        self::assertNotEmpty($user->fresh()->google2fa_secret);
        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Your e-mail is verified. You can now login.');
    }
}
