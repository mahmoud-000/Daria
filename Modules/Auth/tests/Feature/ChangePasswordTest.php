<?php

namespace Modules\Auth\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    public $owner;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->createOwner();
    }

    public function test_change_password_with_no_data()
    {
        $this->post(route('api.auth.change_password'))
            ->assertJsonValidationErrors([
                "current_password" => "The current password field is required.",
                "new_password" => "The new password field is required."
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_no_password_confirmation()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'Passwordsecret1@',
            'new_password' => 'newPasswordsecret1@'
        ]))
            ->assertJsonValidationErrors([
                "new_password" => "The new password confirmation does not match."
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_less_than_8_chars()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'Pa1@',
            'new_password' => 'newPa1@'
        ]))
            ->assertJsonValidationErrors([
                "current_password" => "The current password must be at least 8 characters.",
                "new_password" => [
                    "The new password must be at least 8 characters.",
                    "The new password must be at least 8 characters."
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_has_only_8_uppercase_chars()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'PASSWORD1@',
            'new_password' => 'NEWPASSWORD1@'
        ]))
            ->assertJsonValidationErrors([
                "current_password" => "The current password must contain at least one uppercase and one lowercase letter.",
                "new_password" => [
                    "The new password confirmation does not match.",
                    "The new password must contain at least one uppercase and one lowercase letter."
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_has_only_8_small_chars()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'password1@',
            'new_password' => 'newpassword1@'
        ]))
            ->assertJsonValidationErrors([
                "current_password" => "The current password must contain at least one uppercase and one lowercase letter.",
                "new_password" => [
                    "The new password confirmation does not match.",
                    "The new password must contain at least one uppercase and one lowercase letter."
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_has_only_8_small_chars_and_uppercase()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'Password',
            'new_password' => 'newPassword'
        ]))
            ->assertJsonValidationErrors([
                "current_password" => [
                    "The current password must contain at least one symbol.",
                    "The current password must contain at least one number."
                ],
                "new_password" => [
                    "The new password confirmation does not match.",
                    "The new password must contain at least one symbol.",
                    "The new password must contain at least one number."
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_has_only_8_small_chars_and_uppercase_and_numbers()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'Password1',
            'new_password' => 'newPassword1'
        ]))
            ->assertJsonValidationErrors([
                "current_password" => [
                    "The current password must contain at least one symbol.",
                ],
                "new_password" => [
                    "The new password confirmation does not match.",
                    "The new password must contain at least one symbol.",
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_has_only_8_small_chars_and_uppercase_and_numbers_and_symbol()
    {
        $this->post(route('api.auth.change_password', [
            'current_password' => 'Passwordsecret1@',
            'new_password' => 'newPasswordsecret1@'
        ]))
            ->assertJsonValidationErrors([
                "new_password" => [
                    "The new password confirmation does not match.",
                ]
            ], 'payload')
            ->assertStatus(422);
    }

    public function test_change_password_with_password_validated_and_current_pssword_not_correct()
    {
        $res = $this->post(route('api.auth.change_password', [
            'current_password' => 'Passsecret1@',
            'new_password' => 'newPasswordsecret1@',
            'new_password_confirmation' => 'newPasswordsecret1@'
        ]))->json();
        $this->assertEquals(false, $res['success']);
        $this->assertEquals(trans('auth::auth.current_password_not_correct'), $res['payload']['message']);
    }

    public function test_change_password_with_password_validated_and_current_pssword_correct()
    {
        $res = $this->post(route('api.auth.change_password', [
            'current_password' => 'Passwordsecret1@',
            'new_password' => 'newPasswordsecret1@',
            'new_password_confirmation' => 'newPasswordsecret1@'
        ]))->json();
        $this->assertEquals(true, $res['success']);
        $this->assertEquals(trans('auth::auth.change_password_success'), $res['payload']['message']);
    }
}
