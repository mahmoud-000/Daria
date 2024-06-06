<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    public $user;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->user = $this->createUser();
        $this->createOwner();
    }

    public function test_can_list_users()
    {
        $res = $this->get(route('api.users.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_user_with_required_inputs()
    {
        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@'
        ])->json();

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseHas('users', ['username' => 'testusername']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testusername', 'module' => __('modules.user')]));
    }

    public function test_can_edit_user()
    {
        $res = $this->put(
            route('api.users.update', ['user' => $this->user]),
            [
                'username' => 'newusername',
                'password' => 'Password1@',
                'password_confirmation' => 'Password1@'
            ]
        )->json();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['username' => 'newusername']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newusername', 'module' => __('modules.user')]));
    }

    public function test_can_show_user()
    {
        $userId = $this->user->id;
        $res = $this->get(route('api.users.show', ['user' => $userId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($userId, $res['data']['id']);
    }

    public function test_can_show_user_with_contacts()
    {
        $user = $this->user;
        $this->createContact([
            'contactable_id' => $user->id,
            'contactable_type' => 'User',
        ]);
        $res = $this->get(route('api.users.show', ['user' => $user->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($user->id, $res['data']['id']);
        $this->assertEquals($user->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_user_with_locations()
    {
        $user = $this->user;
        $this->createLocation([
            'locationable_id' => $user->id,
            'locationable_type' => 'User',
        ]);
        $res = $this->get(route('api.users.show', ['user' => $user->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($user->id, $res['data']['id']);
        $this->assertEquals($user->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_user()
    {
        $res = $this->delete(route('api.users.destroy', ['user' => $this->user->id]))->json();
        $this->assertTrue($res['success']);
    }
}
