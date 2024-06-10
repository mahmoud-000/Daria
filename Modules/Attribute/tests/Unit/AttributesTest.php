<?php

namespace Modules\Attribute\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributesTest extends TestCase
{
    use RefreshDatabase;
    public $attribute;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->attribute = $this->createAttribute();
        $this->createOwner();    
    }

    public function test_can_list_attribute()
    {
        $res = $this->get(route('api.attributes.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_attribute_with_required_inputs()
    {
        $res = $this->post(route('api.attributes.store'), [
            'name' => 'testattributename'
        ])->json();
        
        $this->assertDatabaseCount('attributes', 2);
        $this->assertDatabaseHas('attributes', [
            'name' => 'testattributename',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testattributename', 'module' => __('modules.attribute')]));
    }

    public function test_can_edit_attribute()
    {
        $res = $this->put(
            route('api.attributes.update', ['attribute' => $this->attribute]),
            [
                'name' => 'newattributename'
            ]
        )->json();

        $this->assertDatabaseCount('attributes', 1);
        $this->assertDatabaseHas('attributes', [
            'name' => 'newattributename'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newattributename', 'module' => __('modules.attribute')]));
    }

    public function test_can_show_attribute()
    {
        $attributeId = $this->attribute->id;
        $res = $this->get(route('api.attributes.show', ['attribute' => $attributeId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($attributeId, $res['data']['id']);
    }

    public function test_can_delete_attribute()
    {
        $res = $this->delete(route('api.attributes.destroy', ['attribute' => $this->attribute->id]))->json();
        $this->assertTrue($res['success']);
    }
}
