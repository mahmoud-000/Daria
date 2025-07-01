<?php

namespace Modules\Unit\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class UnitsTest extends TestCase
{
    use RefreshDatabase;
    public $unit;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->unit = $this->createUnit();
        $this->createOwner();
    }

    public function test_can_list_units()
    {
        $res = $this->get(route('api.units.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_unit_with_required_inputs()
    {
        $res = $this->post(route('api.units.store'), [
            'name' => 'testunitname',
            'short_name' => 'testunitshortname',
            'is_active' => true,
        ])->json();

        $this->assertDatabaseCount('units', 2);
        $this->assertDatabaseHas('units', [
            'name' => 'testunitname',
            'short_name' => 'testunitshortname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testunitname', 'module' => __('modules.unit')]));
    }

    public function test_can_edit_unit()
    {
        $res = $this->put(
            route('api.units.update', ['unit' => $this->unit]),
            [
                'name' => 'newunitname',
                'short_name' => 'newunitshortname',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('units', 1);
        $this->assertDatabaseHas('units', [
            'name' => 'newunitname',
            'short_name' => 'newunitshortname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newunitname', 'module' => __('modules.unit')]));
    }

    public function test_can_show_unit()
    {
        $unitId = $this->unit->id;
        $res = $this->get(route('api.units.show', ['unit' => $unitId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($unitId, $res['data']['id']);
    }

    public function test_can_delete_unit()
    {
        $res = $this->delete(route('api.units.destroy', ['unit' => $this->unit->id]))->json();
        $this->assertTrue($res['success']);
    }
}
