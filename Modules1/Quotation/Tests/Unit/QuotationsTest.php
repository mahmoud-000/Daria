<?php

namespace Modules\Quotation\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class QuotationsTest extends TestCase
{
    use RefreshDatabase;
    public $quotation;
    public $quotations;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->quotations = $this->createQuotations(10);
        $this->warehouses = $this->createWarehouses(10);
        $this->units = $this->createUnits();
        $this->user = $this->createUsers(['is_active' => true]);
        $this->mockPermissionsWithGates($this->user);
        Sanctum::actingAs($this->user);
    }

    public function test_can_list_quotations()
    {
        $res = $this->get(route('quotations.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_quotation_with_required_inputs()
    {
        $res = $this->post(route('quotations.store'), [
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
            'quotation_unit_id' => 1,
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
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testname', 'module' => __('modules.quotation')]));
    }

    public function test_can_edit_quotation()
    {
        $randomQuotationId = $this->quotations->random()->id;
        $res = $this->put(
            route('quotations.update', ['quotation' => $randomQuotationId]),
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
                'quotation_unit_id' => 1,
                'stock_alert' => 0,
                'variants' => [
                    [
                        "id" => 1,
                        "name" => "Default Variant q",
                        "default" => true,
                        "color" => "#cee5be",
                        "link_preview" => null,
                        "link_download" => null,
                        "quotation_id" => 11,
                    ]
                ],

            ]
        )->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newtestname', 'module' => __('modules.quotation')]));
    }

    public function test_can_show_quotation()
    {
        $randomQuotationId = $this->quotations->random()->id;
        $res = $this->get(route('quotations.show', ['quotation' => $randomQuotationId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomQuotationId, $res['data']['id']);
    }

    public function test_can_show_quotation_with_variants()
    {
        $randomQuotationId = $this->quotations->random()->id;
        $this->createVariants(['quotation_id' => $randomQuotationId, 'color' => 'blue']);
        $res = $this->get(route('quotations.show', ['quotation' => $randomQuotationId]))->json();
        $this->assertEquals(1, count($res['data']['variants']));
        $this->assertEquals($randomQuotationId, $res['data']['id']);
        $this->assertEquals('blue', $res['data']['variants'][0]['color']);
        $this->assertTrue($res['data']['variants'][0]['default']);
    }

    public function test_can_delete_quotation()
    {
        $res = $this->delete(route('quotations.destroy', ['quotation' => $this->quotations->random()->id]))->json();
        $this->assertTrue($res['success']);
    }
}
