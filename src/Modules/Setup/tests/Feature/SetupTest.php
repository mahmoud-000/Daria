<?php

namespace Modules\Setup\Tests\Feature;

use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SetupTest extends TestCase
{
    public function test_can_view_welcome_page()
    {
        $this->get(route('setup.welcome'))
            ->assertSee('Check if all requirements are met.')
            ->assertOk();
    }

    public function test_can_view_requirements_page()
    {
        $this->get(route('setup.requirements'))
            ->assertSee('PHP version')
            ->assertOk();
    }

    public function test_can_view_database_page()
    {
        $this->get(route('setup.database'))
            ->assertSee('Database Configuration')
            ->assertOk();
    }

    public function test_can_not_configure_temporary_database_with_empty_data()
    {
        $this->post(route('setup.configure-database'))
            ->assertSessionHasErrors(['db_host', 'db_port', 'db_name', 'db_user']);
    }

    public function test_can_not_configure_temporary_database_with_correct_data_because_request_not_have_overwrite_data_true()
    {
        $res = $this->post(route('setup.configure-database'), [
            'db_host' => 'localhost',
            'db_port' => 3306,
            'db_name' => 'koncrm',
            'db_user' => 'root',
            'db_password' => ''
        ])
            ->assertRedirect();
    }

    // public function test_can_configure_temporary_database_with_correct_data_and_request_have_overwrite_data()
    // {
    //     $this->post(route('setup.configure-database'), [
    //         'db_host' => 'localhost',
    //         'db_port' => 3306,
    //         'db_name' => 'koncrm',
    //         'db_user' => 'root',
    //         'db_password' => 'password',
    //         'overwrite_data' => true
    //     ])->assertRedirect();
    // }

    public function test_can_view_account_page()
    {
        $this->get(route('setup.account'))
            ->assertSee('name')
            ->assertSee('email')
            ->assertSee('password')
            ->assertSee('password_confirmation')
            ->assertOk();
    }

    public function test_can_not_save_the_account_without_data()
    {
        $this->post(route('setup.save-account'))
            ->assertSessionHasErrors(
                [
                    'username' => 'The username field is required.',
                    'email' => 'The email field is required.',
                    'password' => 'The password field is required.'
                ]
            )
            ->withExceptions(collect(ValidationException::class))
            ->assertStatus(302);
    }

    public function test_can_save_the_account_with_correct_data()
    {
        $this->post(route('setup.save-account', [
            'username' => 'testmahmoud',
            'password' => 'Passwordsecret1@',
            'password_confirmation' => 'Passwordsecret1@',
            'email' => 'dev.mahmoud.adel@gmail.com',
        ]))
            ->assertSessionHasNoErrors(
                [
                    'username' => 'The username field is required.',
                    'email' => 'The email field is required.',
                    'password' => 'The password field is required.',
                    'password_confirmation' => 'The password field is required.'
                ]
            )->assertRedirectToRoute('setup.complete');
    }

    public function test_can_view_the_complete_page()
    {
        $this->get(route('setup.complete'))
            ->assertOk()
            ->assertViewIs('setup::setup.complete');
    }
}
