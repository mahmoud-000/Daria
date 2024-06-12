<?php

namespace Modules\Supplier\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;

class SuppliersTest extends TestCase
{
    use RefreshDatabase;
    public $supplier;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->supplier = $this->createSupplier();
        $this->createOwner();
    }

    public function test_can_list_suppliers()
    {
        $res = $this->get(route('api.suppliers.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_supplier_with_required_inputs()
    {
        $res = $this->post(route('api.suppliers.store'), [
            'fullname' => 'testfullname',
            'type' => 2,
            'company_name' => 'companyName',
            'is_active' => true,
        ])->json();

        $this->assertDatabaseCount('suppliers', 2);
        $this->assertDatabaseHas('suppliers', ['fullname' => 'testfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testfullname', 'module' => __('modules.supplier')]));
    }

    public function test_can_edit_supplier()
    {
        $res = $this->put(
            route('api.suppliers.update', ['supplier' => $this->supplier]),
            [
                'fullname' => 'newfullname',
                'type' => 2,
                'company_name' => 'companyName',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('suppliers', 1);
        $this->assertDatabaseHas('suppliers', ['fullname' => 'newfullname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newfullname', 'module' => __('modules.supplier')]));
    }

    public function test_can_show_supplier()
    {
        $supplierId = $this->supplier->id;
        $res = $this->get(route('api.suppliers.show', ['supplier' => $supplierId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($supplierId, $res['data']['id']);
    }

    public function test_can_show_supplier_with_contacts()
    {
        $supplier = $this->supplier;
        Contact::factory()->create([
            'contactable_id' => $supplier,
            'contactable_type' => 'Supplier',
        ]);
        $res = $this->get(route('api.suppliers.show', ['supplier' => $supplier->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($supplier->id, $res['data']['id']);
        $this->assertEquals($supplier->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_supplier_with_locations()
    {
        $supplier = $this->supplier;
        Location::factory()->create([
            'locationable_id' => $supplier->id,
            'locationable_type' => 'Supplier',
        ]);
        $res = $this->get(route('api.suppliers.show', ['supplier' => $supplier->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($supplier->id, $res['data']['id']);
        $this->assertEquals($supplier->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_supplier()
    {
        $res = $this->delete(route('api.suppliers.destroy', ['supplier' => $this->supplier->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_supplier_with_duuplicate_contacts()
    {
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $dupcontact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.suppliers.store'), [
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
    public function test_can_create_supplier_has_contact_name_already_exists_with_another_supplier()
    {
        $supplier2ID = $this->createSupplier()->id;
        $old_contact = Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $supplier2ID, 'contactable_type' => 'Supplier']);
        $contact_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_not_edit_supplier_with_duuplicate_contacts()
    {
        $supplierId = $this->supplier->id;
        $contacts = [
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $supplierId, 'contactable_type' => 'Supplier'])->toArray(),
            Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $supplierId, 'contactable_type' => 'Supplier'])->toArray()
        ];

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_edit_supplier_has_contact_name_already_exists_with_another_supplier()
    {
        $supplierId = $this->supplier->id;
        $supplier2Id = $this->createSupplier()->id;
        Contact::factory()->create(['name' => 'Contact 1', 'contactable_id' => $supplier2Id, 'contactable_type' => 'Supplier']);
        $contacts = [
            Contact::factory()->make(['name' => 'Contact 1', 'contactable_id' => $supplierId, 'contactable_type' => 'Supplier'])->toArray()
        ];

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_not_create_supplier_with_duuplicate_locations()
    {
        $location_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();
        $duplocation_1 = Contact::factory()->make(['name' => 'Contact 1'])->toArray();

        $res = $this->post(route('api.suppliers.store'), [
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
    public function test_can_create_supplier_has_location_name_already_exists_with_another_supplier()
    {
        $supplier2ID = $this->createSupplier()->id;
        $old_location = Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $supplier2ID, 'locationable_type' => 'Supplier']);
        $location_1 = Location::factory()->make(['name' => 'Location 1'])->toArray();

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_not_edit_supplier_with_duuplicate_locations()
    {
        $supplierId = $this->supplier->id;
        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $supplierId, 'locationable_type' => 'Supplier'])->toArray(),
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $supplierId, 'locationable_type' => 'Supplier'])->toArray()
        ];

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_edit_supplier_has_location_name_already_exists_with_another_supplier()
    {
        $supplierId = $this->supplier->id;
        $supplier2Id = $this->createSupplier()->id;
        Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $supplier2Id, 'locationable_type' => 'Supplier']);

        $locations = [
            Location::factory()->create(['name' => 'Location 1', 'locationable_id' => $supplierId, 'locationable_type' => 'Supplier'])->toArray()
        ];

        $res = $this->post(route('api.suppliers.store'), [
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

    public function test_can_not_create_supplier_has_contact_email_already_exists_with_another_supplier()
    {
        $supplier2ID = $this->createSupplier()->id;
        $old_contact = Contact::factory()->create(['email' => 'email@email.com', 'contactable_id' => $supplier2ID, 'contactable_type' => 'Supplier']);
        $contact_1 = Contact::factory()->make(['email' => 'email@email.com'])->toArray();

        $res = $this->post(route('api.suppliers.store'), [
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
