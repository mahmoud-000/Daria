<?php

namespace Modules\Auth\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
    }
    public function test_auth_user_not_login_with_empty_data()
    {
        $this->post(route('api.auth.login'))
            ->assertJsonValidationErrors([
                'username' => 'The username field is required.',
                'password' => 'The password field is required.',
                'device' => 'The device field is required.',
                'remember' => 'The remember field is required.',
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_auth_user_not_login_with_wrong_data()
    {
        $user = $this->createUser(['is_active' => true]);
        $res = $this->post(route('api.auth.login', [
            'username' => $user->username,
            'password' => 'wrongPasswordsecret1@',
            'remember' => true,
            'device' => 'web'
        ]))
            ->assertUnauthorized()
            ->json();
        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::auth.failed'), $res['payload']['message']);
        $this->assertArrayNotHasKey('token', $res['payload']);
        $this->assertArrayNotHasKey('user', $res['payload']);
    }

    public function test_auth_user_with_correct_data_but_not_active()
    {
        $user = $this->createUser(['is_active' => false]);
        $res = $this->post(route('api.auth.login', [
            'username' => $user->username,
            'password' => 'Passwordsecret1@',
            'remember' => true,
            'device' => 'web',
        ]))
            ->assertUnauthorized()
            ->json();
        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::auth.account_not_active'), $res['payload']['message']);
    }

    public function test_auth_user_with_correct_data_and_active_state()
    {
        $user = $this->createUser(['is_active' => true]);
        $res = $this->post(route('api.auth.login', [
            'username' => $user->username,
            'password' => 'Passwordsecret1@',
            'remember' => true,
            'device' => 'web',
        ]))
            ->assertOk()
            ->json();
        $this->assertEquals(true, $res['success']);
        $this->assertEquals(trans('auth::auth.login', ['user' => $user->username]), $res['payload']['message']);
        $this->assertEquals($user->username, $res['payload']['user']['username']);
        $this->assertArrayHasKey('token', $res['payload']);
    }
}
