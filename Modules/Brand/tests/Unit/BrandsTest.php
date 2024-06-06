<?php

namespace Modules\Brand\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandsTest extends TestCase
{
    use RefreshDatabase;
    public $brand;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->brand = $this->createBrand();
        $this->createOwner();    
    }

    public function test_can_list_brands()
    {
        $res = $this->get(route('api.brands.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_brand_with_required_inputs()
    {
        $res = $this->post(route('api.brands.store'), [
            'name' => 'testbrandname',
        ])->json();

        
        $this->assertDatabaseCount('brands', 2);
        $this->assertDatabaseHas('brands', [
            'name' => 'testbrandname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testbrandname', 'module' => __('modules.brand')]));
    }

    public function test_can_edit_brand()
    {
        $res = $this->put(
            route('api.brands.update', ['brand' => $this->brand]),
            [
                'name' => 'newbrandname',
            ]
        )->json();

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'newbrandname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newbrandname', 'module' => __('modules.brand')]));
    }

    public function test_can_show_brand()
    {
        $brandId = $this->brand->id;
        $res = $this->get(route('api.brands.show', ['brand' => $brandId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($brandId, $res['data']['id']);
    }

    public function test_can_delete_brand()
    {
        $res = $this->delete(route('api.brands.destroy', ['brand' => $this->brand->id]))->json();
        $this->assertTrue($res['success']);
    }
}
