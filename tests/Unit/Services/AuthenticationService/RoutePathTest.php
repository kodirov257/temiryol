<?php

namespace Tests\Unit\Services\AuthenticationService;

use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Env;
use Tests\TestCase;

class RoutePathTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_dashboard_route_path_with_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($user);

        $routeName = AuthenticationService::getHomeRoutePath();

        $this->assertAuthenticated();
        self::assertSame(config('app.url') . '/dashboard', $routeName);
    }

    public function test_get_dashboard_route_path_without_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $routeName = AuthenticationService::getHomeRoutePath($user);

        self::assertSame(config('app.url') . '/dashboard', $routeName);
    }

    public function test_get_home_route_path_with_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $routeName = AuthenticationService::getHomeRoutePath();

        $this->assertAuthenticated();
        self::assertSame(config('app.url'), $routeName);
    }

    public function test_get_home_route_path_without_authenticated_user(): void
    {
        $user = User::factory()->create();

        $routeName = AuthenticationService::getHomeRoutePath($user);

        self::assertSame(config('app.url'), $routeName);
    }
}
