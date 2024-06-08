<?php

namespace Modules\Company\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class CompaniesTest extends TestCase
{
    use RefreshDatabase;
    public $company;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->company = $this->createCompany();
        $this->createOwner();
    }

    public function test_can_list_companies()
    {
        $res = $this->get(route('api.companies.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_company_without_branches()
    {
        $res = $this->post(route('api.companies.store'), [
            'name' => 'testcompanyname',
            'is_active' => 0,
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('branches', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_create_company_with_duuplicate_branches()
    {
        $branch_1 = $this->createBranch(['name' => 'Branch 1'])->toArray();
        $dupbranch_1 = $this->createBranch(['name' => 'Branch 1'])->toArray();

        $res = $this->post(route('api.companies.store'), [
            'name' => 'testcompanyname',
            'is_active' => 0,
            'branches' => [$branch_1, $dupbranch_1]
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('branches.0.name', 'payload')
            ->assertJsonValidationErrorFor('branches.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_create_company_with_branches()
    {
        $companyId = $this->company->id;
        $branches = [
            $this->createBranch(['company_id' => $companyId])->toArray(),
            $this->createBranch(['company_id' => $companyId, 'name' => 'main branch', 'is_main' => true])->toArray()
        ];

        $res = $this->post(route('api.companies.store'), [
            'name' => 'testcompanyname',
            'is_active' => true,
            'branches' => $branches
        ])->json();

        $this->assertDatabaseCount('companies', 2);
        $this->assertDatabaseHas('companies', [
            'name' => 'testcompanyname'
        ]);

        $this->assertDatabaseCount('branches', 2);
        $this->assertDatabaseHas('branches', ['name' => 'main branch', 'is_main' => true]);

        $this->assertDatabaseHas('companies', ['name' => 'testcompanyname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testcompanyname', 'module' => __('modules.company')]));
    }

    public function test_can_not_edit_company_with_duuplicate_branches()
    {
        $companyId = $this->company->id;
        $branches = [
            $this->createBranch(['name' => 'Branch 1', 'company_id' => $companyId])->toArray(),
            $this->createBranch(['name' => 'Branch 1', 'company_id' => $companyId])->toArray()
        ];

        $res = $this->post(route('api.companies.store'), [
            'name' => 'testpipelinename',
            'is_active' => true,
            'branches' => $branches
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('branches.0.name', 'payload')
            ->assertJsonValidationErrorFor('branches.1.name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_edit_company_and_branches()
    {
        $company = $this->company;
        $companyId = $company->id;

        $oldBranches = [
            $this->createBranch(['company_id' => $companyId, 'name' => 'branch 2'])->toArray(),
            $this->createBranch(['company_id' => $companyId, 'name' => 'main branch', 'is_main' => true])->toArray(),
        ];

        $company->branches()->createMany($oldBranches);
        $branch1 = $company->branches->where('name', 'branch 2')->first()->toArray();
        $branch1['name'] = 'Update Branch 2';

        $allBranches = [
            $branch1,
        ];

        $res = $this->put(
            route('api.companies.update', ['company' => $companyId]),
            [
                'name' => 'newcompanyname',
                'is_active' => true,
                'branches' => $allBranches
            ]
        )->json();
        
        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'name' => 'newcompanyname'
        ]);

        $this->assertDatabaseCount('branches', 2);
        $this->assertDatabaseHas('branches', ['name' => 'main branch', 'company_id' => $companyId, 'is_main' => true]);
        $this->assertDatabaseHas('branches', ['name' => 'Update Branch 2', 'company_id' => $companyId, 'is_main' => false]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newcompanyname', 'module' => __('modules.company')]));
    }

    public function test_can_edit_company_and_add_a_new_branches()
    {
        $company = $this->company;
        $companyId = $company->id;
        $branch_1 = $this->createBranch(['company_id' => $companyId, 'name' => 'main branch', 'is_main' => true])->toArray();
        $branch_2 = $this->createBranch(['company_id' => $companyId, 'name' => 'branch 1'])->toArray();
        $branch_3 = $this->createBranch(['company_id' => $companyId, 'name' => 'branch 2'])->toArray();

        $oldBranches = [
            $branch_1,
        ];

        $company->branches()->createMany($oldBranches);
        $companyBranches = $company->branches->toArray();
        $allBranches = [
            ...$companyBranches,
            $branch_2,
            $branch_3,
        ];

        $res = $this->put(
            route('api.companies.update', ['company' => $companyId]),
            [
                'name' => 'newcompanyname',
                'is_active' => true,
                'branches' => $allBranches
            ]
        )->json();

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'name' => 'newcompanyname'
        ]);

        $this->assertDatabaseCount('branches', 3);
        $this->assertDatabaseHas('branches', ['company_id' => $companyId, 'name' => 'main branch', 'is_main' => true]);
        $this->assertDatabaseHas('branches', ['company_id' => $companyId, 'name' => 'branch 1']);
        $this->assertDatabaseHas('branches', ['company_id' => $companyId, 'name' => 'branch 2']);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newcompanyname', 'module' => __('modules.company')]));
    }

    public function test_can_show_company()
    {
        $randomCompanyId = $this->company->id;
        $res = $this->get(route('api.companies.show', ['company' => $randomCompanyId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomCompanyId, $res['data']['id']);
    }

    public function test_can_delete_company()
    {
        $res = $this->delete(route('api.companies.destroy', ['company' => $this->company->id]))->json();
        $this->assertTrue($res['success']);
    }
}
