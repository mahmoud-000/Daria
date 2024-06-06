<?php

namespace Modules\Auth\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgetPasswordTest extends TestCase
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

    public function test_forget_password_with_no_data()
    {
        $this->post(route('api.auth.forget_password'))
            ->assertJsonValidationErrors([
                "email" => "The email field is required.",
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_forget_password_with_not_valid_email()
    {
        $this->post(route('api.auth.forget_password', [
            'email' => 'notValidEmail'
        ]))
            ->assertJsonValidationErrors([
                "email" => "The email must be a valid email address.",
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_forget_password_with_valid_email_but_not_exists()
    {
        $res = $this->post(route('api.auth.forget_password', [
            'email' => 'test@test.com'
        ]))->json();

        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::passwords.user'), $res['payload']['message']);
    }

    public function test_forget_password_with_valid_email_and_exists()
    {
        $res = $this->post(route('api.auth.forget_password', [
            'email' => $this->user->email
        ]))->json();

        $this->assertEquals(true, $res['success']);
        $this->assertEquals(trans('auth::passwords.sent'), $res['payload']['message']);
    }
}
