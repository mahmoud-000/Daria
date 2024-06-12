<?php

namespace Modules\Department\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentsTest extends TestCase
{
    use RefreshDatabase;
    public $department;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->department = $this->createDepartment();
        $this->createOwner();
    }

    public function test_can_list_department()
    {
        $res = $this->get(route('api.departments.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_department_with_required_inputs()
    {
        $res = $this->post(route('api.departments.store'), [
            'name' => 'testdepartmentname',
            'is_active' => true,
        ])->json();

        $this->assertDatabaseCount('departments', 2);
        $this->assertDatabaseHas('departments', [
            'name' => 'testdepartmentname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testdepartmentname', 'module' => __('modules.department')]));
    }

    public function test_can_edit_department()
    {
        $res = $this->put(
            route('api.departments.update', ['department' => $this->department]),
            [
                'name' => 'newdepartmentname',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('departments', 1);
        $this->assertDatabaseHas('departments', [
            'name' => 'newdepartmentname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newdepartmentname', 'module' => __('modules.department')]));
    }

    public function test_can_show_department()
    {
        $catId = $this->department->id;
        $res = $this->get(route('api.departments.show', ['department' => $catId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($catId, $res['data']['id']);
    }

    public function test_can_delete_department()
    {
        $res = $this->delete(route('api.departments.destroy', ['department' => $this->department->id]))->json();
        $this->assertTrue($res['success']);
    }
}
