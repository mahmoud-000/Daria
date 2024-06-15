<?php

namespace Modules\Region\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegionsTest extends TestCase
{
    use RefreshDatabase;
    public $region;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->region = $this->createRegion();
        $this->createOwner();    
    }

    public function test_can_list_regions()
    {
        $res = $this->get(route('api.regions.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_region_with_required_inputs()
    {
        $res = $this->post(route('api.regions.store'), [
            'name' => 'testregionname',
            'is_active' => true,
        ])->json();

        
        $this->assertDatabaseCount('regions', 2);
        $this->assertDatabaseHas('regions', [
            'name' => 'testregionname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testregionname', 'module' => __('modules.region')]));
    }

    public function test_can_edit_region()
    {
        $res = $this->put(
            route('api.regions.update', ['region' => $this->region]),
            [
                'name' => 'newregionname',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('regions', 1);
        $this->assertDatabaseHas('regions', [
            'name' => 'newregionname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newregionname', 'module' => __('modules.region')]));
    }

    public function test_can_show_region()
    {
        $regionId = $this->region->id;
        $res = $this->get(route('api.regions.show', ['region' => $regionId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($regionId, $res['data']['id']);
    }

    public function test_can_delete_region()
    {
        $res = $this->delete(route('api.regions.destroy', ['region' => $this->region->id]))->json();
        $this->assertTrue($res['success']);
    }
}
