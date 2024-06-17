<?php

namespace Modules\ِAccountType\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ِAccountTypesTest extends TestCase
{
    use RefreshDatabase;
    public $ِaccountType;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->ِaccountType = $this->createِAccountType();
        $this->createOwner();    
    }

    public function test_can_list_ِaccountType()
    {
        $res = $this->get(route('api.ِaccountTypes.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_ِaccountType_with_required_inputs()
    {
        $res = $this->post(route('api.ِaccountTypes.store'), [
            'name' => 'testِaccountTypename',
            'is_active' => true
        ])->json();
        
        $this->assertDatabaseCount('ِaccountTypes', 2);
        $this->assertDatabaseHas('ِaccountTypes', [
            'name' => 'testِaccountTypename',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testِaccountTypename', 'module' => __('modules.ِaccountType')]));
    }

    public function test_can_edit_ِaccountType()
    {
        $res = $this->put(
            route('api.ِaccountTypes.update', ['ِaccountType' => $this->ِaccountType]),
            [
                'name' => 'newِaccountTypename',
                'is_active' => true
            ]
        )->json();

        $this->assertDatabaseCount('ِaccountTypes', 1);
        $this->assertDatabaseHas('ِaccountTypes', [
            'name' => 'newِaccountTypename'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newِaccountTypename', 'module' => __('modules.ِaccountType')]));
    }

    public function test_can_show_ِaccountType()
    {
        $ِaccountTypeId = $this->ِaccountType->id;
        $res = $this->get(route('api.ِaccountTypes.show', ['ِaccountType' => $ِaccountTypeId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($ِaccountTypeId, $res['data']['id']);
    }

    public function test_can_delete_ِaccountType()
    {
        $res = $this->delete(route('api.ِaccountTypes.destroy', ['ِaccountType' => $this->ِaccountType->id]))->json();
        $this->assertTrue($res['success']);
    }
}
