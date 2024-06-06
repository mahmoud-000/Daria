<?php

namespace Modules\Auth\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    public $user;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->user = $this->createUser(['is_active' => true]);
    }

    public function test_reset_password_with_no_data()
    {
        $this->post(route('api.auth.reset_password'))
            ->assertJsonValidationErrors([
                "email" => "The email field is required.",
                'password' => "The password field is required.",
                'token' => "The token field is required."
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_reset_password_with_not_valid_email()
    {
        $this->post(route('api.auth.reset_password', [
            'email' => 'notValidEmail'
        ]))
            ->assertJsonValidationErrors([
                "email" => "The email must be a valid email address.",
                'password' => "The password field is required.",
                'token' => "The token field is required."
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_reset_password_with_valid_email_but_not_exists_and_no_token()
    {
        $this->post(route('api.auth.reset_password', [
            'email' => 'test@test.com',
            'password' => 'testPassword1@',
            'password_confirmation' => 'testPassword1@'
        ]))
            ->assertJsonValidationErrors([
                'token' => "The token field is required."
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_reset_password_with_valid_email_but_not_exists()
    {
        $res = $this->post(route('api.auth.reset_password', [
            'email' => 'test@test.com',
            'password' => 'testPassword1@',
            'password_confirmation' => 'testPassword1@',
            'token' => Str::random(60)
        ]))->json();
        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::passwords.user'), $res['payload']['message']);
    }

    public function test_reset_password_with_valid_email_and_exists()
    {
        $res = $this->post(route('api.auth.reset_password', [
            'email' => $this->user->email,
            'password' => 'testPassword1@',
            'password_confirmation' => 'testPassword1@',
            'token' => Password::createToken($this->user)
        ]))->json();
        
        $this->assertEquals(true, $res['success']);
        $this->assertEquals(trans('auth::passwords.reset'), $res['payload']['message']);
    }
}
