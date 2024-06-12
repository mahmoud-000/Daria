<?php

namespace Modules\Customer\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;

class CustomersTest extends TestCase
{
    use RefreshDatabase;
    public $customer;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->customer = $this->createCustomer();
        $this->createOwner();
    }

    public function test_can_list_customers()
    {
        $res = $this->get(route('api.customers.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_customer_with_required_inputs()
    {
        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
        ])->json();

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseHas('customers', ['fullname' => 'testfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testfullname', 'module' => __('modules.customer')]));
    }

    public function test_can_edit_customer()
    {
        $res = $this->put(
            route('api.customers.update', ['customer' => $this->customer]),
            [
                'fullname' => 'newfullname',
                'type' => 2,
                'company_name' => 'companyName',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', ['fullname' => 'newfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newfullname', 'module' => __('modules.customer')]));
    }

    public function test_can_show_customer()
    {
        $customerId = $this->customer->id;
        $res = $this->get(route('api.customers.show', ['customer' => $customerId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($customerId, $res['data']['id']);
    }

    public function test_can_show_customer_with_contacts()
    {
        $customer = $this->customer;
        Contact::factory()->create([
            'contactable_id' => $customer,
            'contactable_type' => 'Customer',
        ]);
        $res = $this->get(route('api.customers.show', ['customer' => $customer->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($customer->id, $res['data']['id']);
        $this->assertEquals($customer->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_customer_with_locations()
    {
        $customer = $this->customer;
        Location::factory()->create([
            'locationable_id' => $customer->id,
            'locationable_type' => 'Customer',
        ]);
        $res = $this->get(route('api.customers.show', ['customer' => $customer->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($customer->id, $res['data']['id']);
        $this->assertEquals($customer->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_customer()
    {
        $res = $this->delete(route('api.customers.destroy', ['customer' => $this->customer->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_customer_with_duuplicate_contacts()
    {
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $dupcontact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
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
    public function test_can_create_customer_has_contact_name_already_exists_with_another_customer()
    {
        $customer2ID = $this->createCustomer()->id;
        $old_contact = Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $customer2ID, 'contactable_type' => 'Customer']);
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
            'contacts' => [$contact_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_customer_with_duuplicate_contacts()
    {
        $customerId = $this->customer->id;
        $contacts = [
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $customerId, 'contactable_type' => 'Customer'])->toArray(),
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $customerId, 'contactable_type' => 'Customer'])->toArray()
        ];

        $res = $this->post(route('api.customers.store'), [
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

    public function test_can_edit_customer_has_contact_name_already_exists_with_another_customer()
    {
        $customerId = $this->customer->id;
        $customer2Id = $this->createCustomer()->id;
        Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $customer2Id, 'contactable_type' => 'Customer']);
        $contacts = [
            Contact::factory()->make(['name' => 'Contact 1', 'contactable_id' => $customerId, 'contactable_type' => 'Customer'])->toArray()
        ];

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
            'contacts' => $contacts
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_customer_with_duuplicate_locations()
    {
        $location_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $duplocation_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
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
    public function test_can_create_customer_has_location_name_already_exists_with_another_customer()
    {
        $customer2ID = $this->createCustomer()->id;
        $old_location = Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $customer2ID, 'locationable_type' => 'Customer']);
        $location_1 = Location::factory()->make(['name' => 'Location 1'])->toArray();

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
            'locations' => [$location_1]
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_edit_customer_with_duuplicate_locations()
    {
        $customerId = $this->customer->id;
        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $customerId, 'locationable_type' => 'Customer'])->toArray(),
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $customerId, 'locationable_type' => 'Customer'])->toArray()
        ];

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
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

    public function test_can_edit_customer_has_location_name_already_exists_with_another_customer()
    {
        $customerId = $this->customer->id;
        $customer2Id = $this->createCustomer()->id;
        Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $customer2Id, 'locationable_type' => 'Customer']);

        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $customerId, 'locationable_type' => 'Customer'])->toArray()
        ];

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
            'locations' => $locations
        ])
            ->assertStatus(200)
            ->json();

        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_customer_has_contact_email_already_exists_with_another_customer()
    {
        $customer2ID = $this->createCustomer()->id;
        $old_contact = Contact::factory()->create(['email' => 'email@email.com', 'contactable_id' => $customer2ID, 'contactable_type' => 'Customer']);
        $contact_1 = Contact::factory()->make(['email' => 'email@email.com'])->toArray();

        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
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
