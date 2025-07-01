<?php

namespace Modules\Delegate\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;

class DelegatesTest extends TestCase
{
    use RefreshDatabase;
    public $delegate;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->delegate = $this->createDelegate();
        $this->createOwner();
    }

    public function test_can_list_delegates()
    {
        $res = $this->get(route('api.delegates.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_delegate_with_required_inputs()
    {
        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
            'is_active' => true,
        ])->json();

        $this->assertDatabaseCount('delegates', 2);
        $this->assertDatabaseHas('delegates', ['fullname' => 'testfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testfullname', 'module' => __('modules.delegate')]));
    }

    public function test_can_edit_delegate()
    {
        $res = $this->put(
            route('api.delegates.update', ['delegate' => $this->delegate]),
            [
                'fullname' => 'newfullname',
                'type' => 2,
                'commission_type' => 2,
                'commission' => 10,
                'company_name' => 'companyName',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('delegates', 1);
        $this->assertDatabaseHas('delegates', ['fullname' => 'newfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newfullname', 'module' => __('modules.delegate')]));
    }

    public function test_can_show_delegate()
    {
        $delegateId = $this->delegate->id;
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegateId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($delegateId, $res['data']['id']);
    }

    public function test_can_show_delegate_with_contacts()
    {
        $delegate = $this->delegate;
        Contact::factory()->create([
            'contactable_id' => $delegate,
            'contactable_type' => 'Delegate',
        ]);
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegate->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($delegate->id, $res['data']['id']);
        $this->assertEquals($delegate->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_delegate_with_locations()
    {
        $delegate = $this->delegate;
        Location::factory()->create([
            'locationable_id' => $delegate->id,
            'locationable_type' => 'Delegate',
        ]);
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegate->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($delegate->id, $res['data']['id']);
        $this->assertEquals($delegate->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_delegate()
    {
        $res = $this->delete(route('api.delegates.destroy', ['delegate' => $this->delegate->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_delegate_with_duuplicate_contacts()
    {
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $dupcontact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
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
    public function test_can_create_delegate_has_contact_name_already_exists_with_another_delegate()
    {
        $delegate2ID = $this->createDelegate()->id;
        $old_contact = Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $delegate2ID, 'contactable_type' => 'Delegate']);
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
            'is_active' => true,
            'contacts' => [$contact_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_delegate_with_duuplicate_contacts()
    {
        $delegateId = $this->delegate->id;
        $contacts = [
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $delegateId, 'contactable_type' => 'Delegate'])->toArray(),
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $delegateId, 'contactable_type' => 'Delegate'])->toArray()
        ];

        $res = $this->post(route('api.delegates.store'), [
            'name' => 'testfullname',
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

    public function test_can_edit_delegate_has_contact_name_already_exists_with_another_delegate()
    {
        $delegateId = $this->delegate->id;
        $delegate2Id = $this->createDelegate()->id;
        Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $delegate2Id, 'contactable_type' => 'Delegate']);
        $contacts = [
            Contact::factory()->make(['name' => 'Contact 1', 'contactable_id' => $delegateId, 'contactable_type' => 'Delegate'])->toArray()
        ];

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
            'is_active' => true,
            'contacts' => $contacts
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_delegate_with_duuplicate_locations()
    {
        $location_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $duplocation_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
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
    public function test_can_create_delegate_has_location_name_already_exists_with_another_delegate()
    {
        $delegate2ID = $this->createDelegate()->id;
        $old_location = Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $delegate2ID, 'locationable_type' => 'Delegate']);
        $location_1 = Location::factory()->make(['name' => 'Location 1'])->toArray();

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
            'is_active' => true,
            'locations' => [$location_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_delegate_with_duuplicate_locations()
    {
        $delegateId = $this->delegate->id;
        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $delegateId, 'locationable_type' => 'Delegate'])->toArray(),
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $delegateId, 'locationable_type' => 'Delegate'])->toArray()
        ];

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
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

    public function test_can_edit_delegate_has_location_name_already_exists_with_another_delegate()
    {
        $delegateId = $this->delegate->id;
        $delegate2Id = $this->createDelegate()->id;
        Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $delegate2Id, 'locationable_type' => 'Delegate']);

        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $delegateId, 'locationable_type' => 'Delegate'])->toArray()
        ];

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
            'is_active' => true,
            'locations' => $locations
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_delegate_has_contact_email_already_exists_with_another_delegate()
    {
        $delegate2ID = $this->createDelegate()->id;
        $old_contact = Contact::factory()->create(['email' => 'email@email.com', 'contactable_id' => $delegate2ID, 'contactable_type' => 'Delegate']);
        $contact_1 = Contact::factory()->make(['email' => 'email@email.com'])->toArray();

        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'commission_type' => 2,
            'commission' => 10,
            'company_name' => 'companyName',
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
