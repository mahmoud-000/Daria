<?php

namespace Modules\Category\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;
    public $category;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->category = $this->createCategory();
        $this->createOwner();
    }

    public function test_can_list_category()
    {
        $res = $this->get(route('api.categories.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_category_with_required_inputs()
    {
        $res = $this->post(route('api.categories.store'), [
            'name' => 'testcategoryname',
        ])->json();

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', [
            'name' => 'testcategoryname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testcategoryname', 'module' => __('modules.category')]));
    }

    public function test_can_edit_category()
    {
        $res = $this->put(
            route('api.categories.update', ['category' => $this->category]),
            [
                'name' => 'newcategoryname',
            ]
        )->json();

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'name' => 'newcategoryname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newcategoryname', 'module' => __('modules.category')]));
    }

    public function test_can_show_category()
    {
        $catId = $this->category->id;
        $res = $this->get(route('api.categories.show', ['category' => $catId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($catId, $res['data']['id']);
    }

    public function test_can_delete_category()
    {
        $res = $this->delete(route('api.categories.destroy', ['category' => $this->category->id]))->json();
        $this->assertTrue($res['success']);
    }
}
