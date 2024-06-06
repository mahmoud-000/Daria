<?php

namespace Modules\Sale\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class SalesTest extends TestCase
{
    use RefreshDatabase;
    public $sale;
    public $sales;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->sales = $this->createSales(10);
        $this->warehouses = $this->createWarehouses(10);
        $this->units = $this->createUnits();
        $this->user = $this->createUsers(['is_active' => true]);
        $this->mockPermissionsWithGates($this->user);
        Sanctum::actingAs($this->user);
    }

    public function test_can_list_sales()
    {
        $res = $this->get(route('sales.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_sale_with_required_inputs()
    {
        $res = $this->post(route('sales.store'), [
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
            'sale_unit_id' => 1,
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
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testname', 'module' => __('modules.sale')]));
    }

    public function test_can_edit_sale()
    {
        $randomSaleId = $this->sales->random()->id;
        $res = $this->put(
            route('sales.update', ['sale' => $randomSaleId]),
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
                'sale_unit_id' => 1,
                'stock_alert' => 0,
                'variants' => [
                    [
                        "id" => 1,
                        "name" => "Default Variant q",
                        "default" => true,
                        "color" => "#cee5be",
                        "link_preview" => null,
                        "link_download" => null,
                        "sale_id" => 11,
                    ]
                ],

            ]
        )->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newtestname', 'module' => __('modules.sale')]));
    }

    public function test_can_show_sale()
    {
        $randomSaleId = $this->sales->random()->id;
        $res = $this->get(route('sales.show', ['sale' => $randomSaleId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomSaleId, $res['data']['id']);
    }

    public function test_can_show_sale_with_variants()
    {
        $randomSaleId = $this->sales->random()->id;
        $this->createVariants(['sale_id' => $randomSaleId, 'color' => 'blue']);
        $res = $this->get(route('sales.show', ['sale' => $randomSaleId]))->json();
        $this->assertEquals(1, count($res['data']['variants']));
        $this->assertEquals($randomSaleId, $res['data']['id']);
        $this->assertEquals('blue', $res['data']['variants'][0]['color']);
        $this->assertTrue($res['data']['variants'][0]['default']);
    }

    public function test_can_delete_sale()
    {
        $res = $this->delete(route('sales.destroy', ['sale' => $this->sales->random()->id]))->json();
        $this->assertTrue($res['success']);
    }
}
