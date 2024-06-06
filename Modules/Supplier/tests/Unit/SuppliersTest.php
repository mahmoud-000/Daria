<?php

namespace Modules\Supplier\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'fullname' => 'testsuppliername',
            'type' => 2,
            'company_name' => 'testcompanyname',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@'
        ])->json();

        $this->assertDatabaseCount('suppliers', 2);
        $this->assertDatabaseHas('suppliers', [
            'fullname' => 'testsuppliername',
            'company_name' => 'testcompanyname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testsuppliername', 'module' => __('modules.supplier')]));
    }

    public function test_can_edit_supplier()
    {
        $res = $this->put(
            route('api.suppliers.update', ['supplier' => $this->supplier]),
            [
                'fullname' => 'newsuppliername',
                'type' => 1,
                'company_name' => 'newcompanyname',
                'password' => 'Password1@',
                'password_confirmation' => 'Password1@'
            ]
        )->json();

        $this->assertDatabaseCount('suppliers', 1);
        $this->assertDatabaseHas('suppliers', [
            'fullname' => 'newsuppliername',
            'company_name' => 'newcompanyname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newsuppliername', 'module' => __('modules.supplier')]));
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
        $this->createContact([
            'contactable_id' => $supplier->id,
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
        $this->createLocation([
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
}
