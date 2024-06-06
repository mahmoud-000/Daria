<?php

namespace Modules\PurchaseReturn\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class PurchaseReturnsTest extends TestCase
{
    use RefreshDatabase;
    public $purchase_return;
    public $purchase_returns;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->purchase_returns = $this->createPurchaseReturns(10);
        $this->warehouses = $this->createWarehouses(10);
        $this->units = $this->createUnits();
        $this->user = $this->createUsers(['is_active' => true]);
        $this->mockPermissionsWithGates($this->user);
        Sanctum::actingAs($this->user);
    }

    public function test_can_list_purchase_returns()
    {
        $res = $this->get(route('purchase_returns.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_purchase_return_with_required_inputs()
    {
        $res = $this->post(route('purchase_returns.store'), [
            'name' => 'testname',
            'label' => 'testlabel',
            'barcode_type' => 1,
            'barcode' => 'testbarcode',
            'currency' => config('setting.currency'),
            'cost' => 10.00,
            'price' => 15.00,
            'tax_type' => 1,
            'tax' => 10,
            'unit_id' => 1,
            'sale_unit_id' => 1,
            'purchase_return_unit_id' => 1,
            'stock_alert' => 0,
            'variants' => [
                [
                    'name'      => 'default',
                    'color'     => 'red',
                    'default'   => true
                ],
                [
                    'name'      => 'test',
                    'color'     => 'white',
                    'default'   => false
                ]
            ]
        ])->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testname', 'module' => __('modules.purchase_return')]));
    }

    public function test_can_edit_purchase_return()
    {
        $randomPurchaseReturnId = $this->purchase_returns->random()->id;
        $res = $this->put(
            route('purchase_returns.update', ['purchase_return' => $randomPurchaseReturnId]),
            [
                'name' => 'newtestname',
                'label' => 'testlabel',
                'barcode_type' => 1,
                'barcode' => 'testbarcode',
                'currency' => config('setting.currency'),
                'cost' => 10.00,
                'price' => 15.00,
                'tax_type' => 1,
                'tax' => 10,
                'unit_id' => 1,
                'sale_unit_id' => 1,
                'purchase_return_unit_id' => 1,
                'stock_alert' => 0,
                'variants' => [
                    [
                        "id" => 1,
                        "name" => "Default Variant q",
                        "default" => true,
                        "color" => "#cee5be",
                        "link_preview" => null,
                        "link_download" => null,
                        "purchase_return_id" => 11,
                    ]
                ],

            ]
        )->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newtestname', 'module' => __('modules.purchase_return')]));
    }

    public function test_can_show_purchase_return()
    {
        $randomPurchaseReturnId = $this->purchase_returns->random()->id;
        $res = $this->get(route('purchase_returns.show', ['purchase_return' => $randomPurchaseReturnId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomPurchaseReturnId, $res['data']['id']);
    }

    public function test_can_show_purchase_return_with_variants()
    {
        $randomPurchaseReturnId = $this->purchase_returns->random()->id;
        $this->createVariants(['purchase_return_id' => $randomPurchaseReturnId, 'color' => 'blue']);
        $res = $this->get(route('purchase_returns.show', ['purchase_return' => $randomPurchaseReturnId]))->json();
        $this->assertEquals(1, count($res['data']['variants']));
        $this->assertEquals($randomPurchaseReturnId, $res['data']['id']);
        $this->assertEquals('blue', $res['data']['variants'][0]['color']);
        $this->assertTrue($res['data']['variants'][0]['default']);
    }

    public function test_can_delete_purchase_return()
    {
        $res = $this->delete(route('purchase_returns.destroy', ['purchase_return' => $this->purchase_returns->random()->id]))->json();
        $this->assertTrue($res['success']);
    }
}
