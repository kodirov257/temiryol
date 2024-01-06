<?php

namespace Tests\Feature\Auth;

use App\Models\User\Profile;
use App\Models\User\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/verify-email');
    }

    public function test_register_error_no_password(): void
    {

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'TestUser',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors();
        $response->assertSessionHas('error', 'User with this username, email or phone exists.');
        $response->assertRedirect('/register');
    }

    public function test_register_error_wrong_email(): void
    {

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'TestUser',
            'email' => 'wrong-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertSessionHas('error', 'User with this username, email or phone exists.');
        $response->assertRedirect('/register');
    }
}
