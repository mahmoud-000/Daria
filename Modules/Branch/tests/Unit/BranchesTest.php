<?php

namespace Modules\Branch\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Company\Models\Company;
use Modules\Branch\Models\Branch;

class BranchesTest extends TestCase
{
    use RefreshDatabase;
    public $branch;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->branch = Branch::factory()->for(Company::factory()->create())->create();

        $this->createOwner();
    }

    public function test_can_list_branches()
    {
        $res = $this->get(route('api.branches.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_branch_with_required_inputs()
    {
        $companyId = $this->createCompany()->id;

        $res = $this->post(route('api.branches.store'), [
            'name' => 'testbranchname',
            'is_main' => true,
            'is_active' => true,
            'company_id' => $companyId,
        ])->json();

        $this->assertDatabaseCount('branches', 2);
        $this->assertDatabaseHas('branches', [
            'name' => 'testbranchname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testbranchname', 'module' => __('modules.branch')]));
    }

    public function test_can_edit_branch()
    {
        $res = $this->put(
            route('api.branches.update', ['branch' => $this->branch]),
            [
                'name' => 'newbranchname',
                'is_main' => true,
                'is_active' => true,
                'company_id' => $this->branch->company_id,
            ]
        )->json();

        $this->assertDatabaseCount('branches', 1);
        $this->assertDatabaseHas('branches', [
            'name' => 'newbranchname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newbranchname', 'module' => __('modules.branch')]));
    }

    public function test_can_show_branch()
    {
        $branchId = $this->branch->id;
        $res = $this->get(route('api.branches.show', ['branch' => $branchId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($branchId, $res['data']['id']);
    }

    public function test_can_delete_branch()
    {
        $res = $this->delete(route('api.branches.destroy', ['branch' => $this->branch->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_branch_with_name_already_exists_in_same_company()
    {
        $company = $this->createCompany();
        $old_branch = Branch::factory()->create(['name' => 'Branch 1', 'company_id' => $company->id]);
        $new_branch = $this->createBranch(['name' => 'Branch 1', 'company_id' => $company->id])->toArray();

        $res = $this->post(route('api.branches.store'), [
            ...$new_branch
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_edit_branch_with_name_already_exists_in_same_company()
    {
        $company = $this->createCompany();

        $oldBranchInDB = Branch::factory()->create(['name' => 'Old Branch', 'company_id' => $company->id]);
        $branchInDB = Branch::factory()->create(['name' => 'Branch 1', 'company_id' => $company->id]);
        $branch = $this->createBranch(['name' => 'Branch 1', 'company_id' => $company->id])->toArray();

        $res = $this->put(
            route('api.branches.update', ['branch' => $branchInDB]),
            [
                'name' => $oldBranchInDB->name,
                'company_id' => $company->id
            ]
        )
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }
}
