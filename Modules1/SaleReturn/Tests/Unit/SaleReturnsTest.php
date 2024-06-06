<?php

namespace Modules\SaleReturn\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class SaleReturnsTest extends TestCase
{
    use RefreshDatabase;
    public $sale_return;
    public $sale_returns;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->sale_returns = $this->createSaleReturns(10);
        $this->warehouses = $this->createWarehouses(10);
        $this->units = $this->createUnits();
        $this->user = $this->createUsers(['is_active' => true]);
        $this->mockPermissionsWithGates($this->user);
        Sanctum::actingAs($this->user);
    }

    public function test_can_list_sale_returns()
    {
        $res = $this->get(route('sale_returns.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_sale_return_with_required_inputs()
    {
        $res = $this->post(route('sale_returns.store'), [
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
            'sale_return_unit_id' => 1,
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
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testname', 'module' => __('modules.sale_return')]));
    }

    public function test_can_edit_sale_return()
    {
        $randomSaleReturnId = $this->sale_returns->random()->id;
        $res = $this->put(
            route('sale_returns.update', ['sale_return' => $randomSaleReturnId]),
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
                'sale_return_unit_id' => 1,
                'stock_alert' => 0,
                'variants' => [
                    [
                        "id" => 1,
                        "name" => "Default Variant q",
                        "default" => true,
                        "color" => "#cee5be",
                        "link_preview" => null,
                        "link_download" => null,
                        "sale_return_id" => 11,
                    ]
                ],

            ]
        )->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newtestname', 'module' => __('modules.sale_return')]));
    }

    public function test_can_show_sale_return()
    {
        $randomSaleReturnId = $this->sale_returns->random()->id;
        $res = $this->get(route('sale_returns.show', ['sale_return' => $randomSaleReturnId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomSaleReturnId, $res['data']['id']);
    }

    public function test_can_show_sale_return_with_variants()
    {
        $randomSaleReturnId = $this->sale_returns->random()->id;
        $this->createVariants(['sale_return_id' => $randomSaleReturnId, 'color' => 'blue']);
        $res = $this->get(route('sale_returns.show', ['sale_return' => $randomSaleReturnId]))->json();
        $this->assertEquals(1, count($res['data']['variants']));
        $this->assertEquals($randomSaleReturnId, $res['data']['id']);
        $this->assertEquals('blue', $res['data']['variants'][0]['color']);
        $this->assertTrue($res['data']['variants'][0]['default']);
    }

    public function test_can_delete_sale_return()
    {
        $res = $this->delete(route('sale_returns.destroy', ['sale_return' => $this->sale_returns->random()->id]))->json();
        $this->assertTrue($res['success']);
    }
}
