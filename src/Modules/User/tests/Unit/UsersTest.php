<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;

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
            'password_confirmation' => 'Password1@',
            'is_active' => true,
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
                'password_confirmation' => 'Password1@',
                'is_active' => true,
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
        Contact::factory()->create([
            'contactable_id' => $user,
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
        Location::factory()->create([
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

    public function test_can_not_create_user_with_duuplicate_contacts()
    {
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $dupcontact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'contacts' => [$contact_1, $dupcontact_1]
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('contacts.0.name', 'payload')
            ->assertJsonValidationErrorFor('contacts.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }
    public function test_can_create_user_has_contact_name_already_exists_with_another_user()
    {
        $user2ID = $this->createUser()->id;
        $old_contact = Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $user2ID, 'contactable_type' => 'User']);
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'contacts' => [$contact_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_user_with_duuplicate_contacts()
    {
        $userId = $this->user->id;
        $contacts = [
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $userId, 'contactable_type' => 'User'])->toArray(),
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $userId, 'contactable_type' => 'User'])->toArray()
        ];

        $res = $this->post(route('api.users.store'), [
            'name' => 'testusername',
            'is_active' => true,
            'contacts' => $contacts
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('contacts.0.name', 'payload')
            ->assertJsonValidationErrorFor('contacts.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_edit_user_has_contact_name_already_exists_with_another_user()
    {
        $userId = $this->user->id;
        $user2Id = $this->createUser()->id;
        Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $user2Id, 'contactable_type' => 'User']);
        $contacts = [
            Contact::factory()->make(['name' => 'Contact 1', 'contactable_id' => $userId, 'contactable_type' => 'User'])->toArray()
        ];

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'contacts' => $contacts
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_user_with_duuplicate_locations()
    {
        $location_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $duplocation_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'locations' => [$location_1, $duplocation_1]
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('locations.0.name', 'payload')
            ->assertJsonValidationErrorFor('locations.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }
    public function test_can_create_user_has_location_name_already_exists_with_another_user()
    {
        $user2ID = $this->createUser()->id;
        $old_location = Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $user2ID, 'locationable_type' => 'User']);
        $location_1 = Location::factory()->make(['name' => 'Location 1'])->toArray();

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'locations' => [$location_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_user_with_duuplicate_locations()
    {
        $userId = $this->user->id;
        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $userId, 'locationable_type' => 'User'])->toArray(),
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $userId, 'locationable_type' => 'User'])->toArray()
        ];

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'locations' => $locations
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('locations.0.name', 'payload')
            ->assertJsonValidationErrorFor('locations.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_edit_user_has_location_name_already_exists_with_another_user()
    {
        $userId = $this->user->id;
        $user2Id = $this->createUser()->id;
        Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $user2Id, 'locationable_type' => 'User']);

        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $userId, 'locationable_type' => 'User'])->toArray()
        ];

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'locations' => $locations
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_user_has_contact_email_already_exists_with_another_user()
    {
        $user2ID = $this->createUser()->id;
        $old_contact = Contact::factory()->create(['email' => 'email@email.com', 'contactable_id' => $user2ID, 'contactable_type' => 'User']);
        $contact_1 = Contact::factory()->make(['email' => 'email@email.com'])->toArray();

        $res = $this->post(route('api.users.store'), [
            'username' => 'testusername',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@',
            'is_active' => true,
            'contacts' => [$contact_1]
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('contacts.0.email', 'payload')
            ->json();
        
        $this->assertFalse($res['success']);
    }
}
