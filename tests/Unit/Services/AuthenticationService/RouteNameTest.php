<?php

namespace Tests\Unit\Services\AuthenticationService;

use App\Models\User\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_dashboard_route_name_with_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($user);

        $routeName = AuthenticationService::getHomeRouteName();

        $this->assertAuthenticated();
        self::assertSame('dashboard.home', $routeName);
    }

    public function test_get_dashboard_route_name_without_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $routeName = AuthenticationService::getHomeRouteName($user);

        self::assertSame('dashboard.home', $routeName);
    }

    public function test_get_home_route_name_with_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $routeName = AuthenticationService::getHomeRouteName();

        $this->assertAuthenticated();
        self::assertSame('home', $routeName);
    }

    public function test_get_home_route_name_without_authenticated_user(): void
    {
        $user = User::factory()->create();

        $routeName = AuthenticationService::getHomeRouteName($user);

        self::assertSame('home', $routeName);
    }
}
