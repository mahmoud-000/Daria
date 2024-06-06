<?php

namespace Modules\Delegate\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DelegatesTest extends TestCase
{
    use RefreshDatabase;
    public $delegate;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->delegate = $this->createDelegate();
        $this->createOwner();
    }

    public function test_can_list_delegates()
    {
        $res = $this->get(route('api.delegates.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_delegate_with_required_inputs()
    {
        $res = $this->post(route('api.delegates.store'), [
            'fullname' => 'testdelegatename',
            'company_name' => 'testcompanyname',
            'type' => 1,
            'commission_type' => 2,
            'commission' => 100,
        ])->json();

        $this->assertDatabaseCount('delegates', 2);
        $this->assertDatabaseHas('delegates', [
            'fullname' => 'testdelegatename',
            'company_name' => 'testcompanyname',
            'type' => 1,
            'commission_type' => 2,
            'commission' => 100,
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testdelegatename', 'module' => __('modules.delegate')]));
    }

    public function test_can_edit_delegate()
    {
        $res = $this->put(
            route('api.delegates.update', ['delegate' => $this->delegate]),
            [
                'fullname' => 'newdelegatename',
                'company_name' => 'newcompanyname',
                'type' => 2,
                'commission_type' => 1,
            'commission' => 200,
            ]
        )->json();

        $this->assertDatabaseCount('delegates', 1);
        $this->assertDatabaseHas('delegates', [
            'fullname' => 'newdelegatename',
            'company_name' => 'newcompanyname',
            'type' => 2,
            'commission_type' => 1,
            'commission' => 200,
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newdelegatename', 'module' => __('modules.delegate')]));
    }

    public function test_can_show_delegate()
    {
        $delegateId = $this->delegate->id;
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegateId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($delegateId, $res['data']['id']);
    }

    public function test_can_show_delegate_with_contacts()
    {
        $delegate = $this->delegate;
        $this->createContact([
            'contactable_id' => $delegate->id,
            'contactable_type' => 'Delegate',
        ]);
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegate->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($delegate->id, $res['data']['id']);
        $this->assertEquals($delegate->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_delegate_with_locations()
    {
        $delegate = $this->delegate;
        $this->createLocation([
            'locationable_id' => $delegate->id,
            'locationable_type' => 'Delegate',
        ]);
        $res = $this->get(route('api.delegates.show', ['delegate' => $delegate->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($delegate->id, $res['data']['id']);
        $this->assertEquals($delegate->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_delegate()
    {
        $res = $this->delete(route('api.delegates.destroy', ['delegate' => $this->delegate->id]))->json();
        $this->assertTrue($res['success']);
    }
}
