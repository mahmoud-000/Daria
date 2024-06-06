<?php

namespace Modules\Auth\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
    }

    public function test_logout_not_auth_user()
    {
        $this->withoutMiddleware();
        $res = $this->postJson(route('api.auth.logout'));
        
        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::auth.no_auth_user'), $res['payload']['message']);
    }

    public function test_logout_auth_user()
    {
        $user = $this->createUser(['is_active' => true]);
        Sanctum::actingAs($user);
        $res = $this->post(route('api.auth.logout'))->json();
        $this->assertEquals(true, $res['success']);
        $this->assertEquals(trans('auth::auth.logout', ['user' => $user->username]), $res['payload']['message']);
    }
}
