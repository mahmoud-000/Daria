<?php

namespace Modules\Warehouse\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehousesTest extends TestCase
{
    use RefreshDatabase;
    public $warehouse;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->warehouse = $this->createWarehouse();
        $this->createOwner();    
    }

    public function test_can_list_warehouse()
    {
        $res = $this->get(route('api.warehouses.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_warehouse_with_required_inputs()
    {
        $res = $this->post(route('api.warehouses.store'), [
            'name' => 'testwarehousename'
        ])->json();
        
        $this->assertDatabaseCount('warehouses', 2);
        $this->assertDatabaseHas('warehouses', [
            'name' => 'testwarehousename',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testwarehousename', 'module' => __('modules.warehouse')]));
    }

    public function test_can_edit_warehouse()
    {
        $res = $this->put(
            route('api.warehouses.update', ['warehouse' => $this->warehouse]),
            [
                'name' => 'newwarehousename'
            ]
        )->json();

        $this->assertDatabaseCount('warehouses', 1);
        $this->assertDatabaseHas('warehouses', [
            'name' => 'newwarehousename'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newwarehousename', 'module' => __('modules.warehouse')]));
    }

    public function test_can_show_warehouse()
    {
        $warehouseId = $this->warehouse->id;
        $res = $this->get(route('api.warehouses.show', ['warehouse' => $warehouseId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($warehouseId, $res['data']['id']);
    }

    public function test_can_delete_warehouse()
    {
        $res = $this->delete(route('api.warehouses.destroy', ['warehouse' => $this->warehouse->id]))->json();
        $this->assertTrue($res['success']);
    }
}
