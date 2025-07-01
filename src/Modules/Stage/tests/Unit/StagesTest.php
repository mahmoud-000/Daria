<?php

namespace Modules\Stage\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Models\Stage;

class StagesTest extends TestCase
{
    use RefreshDatabase;
    public $stage;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->stage = Stage::factory()->for(Pipeline::factory()->create())->create();

        $this->createOwner();
    }

    public function test_can_list_stage()
    {
        $res = $this->get(route('api.stages.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_stage_with_required_inputs()
    {
        $pipelineId = $this->createPipeline()->id;

        $res = $this->post(route('api.stages.store'), [
            'name' => 'teststagename',
            'color' => '#000000',
            'complete' => 33,
            'is_default' => false,
            'is_active' => true,
            'pipeline_id' => $pipelineId,
        ])->json();

        $this->assertDatabaseCount('stages', 4);
        $this->assertDatabaseHas('stages', [
            'name' => 'teststagename',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'teststagename', 'module' => __('modules.stage')]));
    }

    public function test_can_edit_stage()
    {
        $res = $this->put(
            route('api.stages.update', ['stage' => $this->stage]),
            [
                'name' => 'newstagename',
                'color' => '#ffffff',
                'complete' => 66,
                'is_default' => false,
                'is_active' => true,
                'pipeline_id' => $this->stage->pipeline_id,
            ]
        )->json();

        $this->assertDatabaseCount('stages', 1);
        $this->assertDatabaseHas('stages', [
            'name' => 'newstagename'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newstagename', 'module' => __('modules.stage')]));
    }

    public function test_can_show_stage()
    {
        $stageId = $this->stage->id;
        $res = $this->get(route('api.stages.show', ['stage' => $stageId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($stageId, $res['data']['id']);
    }

    public function test_can_delete_stage()
    {
        $res = $this->delete(route('api.stages.destroy', ['stage' => $this->stage->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_stage_with_name_already_exists_in_same_variant()
    {
        $pipeline = $this->createPipeline();
        $old_stage = Stage::factory()->create(['name' => 'Stage 1', 'pipeline_id' => $pipeline->id]);
        $new_stage = $this->createStage(['name' => 'Stage 1', 'pipeline_id' => $pipeline->id])->toArray();

        $res = $this->post(route('api.stages.store'), [
            ...$new_stage
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_edit_stage_with_name_already_exists_in_same_pipeline()
    {
        $pipeline = $this->createPipeline();

        $oldStageInDB = Stage::factory()->create(['name' => 'Old Stage', 'pipeline_id' => $pipeline->id]);
        $stageInDB = Stage::factory()->create(['name' => 'Stage 1', 'pipeline_id' => $pipeline->id]);
        $stage = $this->createStage(['name' => 'Stage 1', 'pipeline_id' => $pipeline->id])->toArray();

        $res = $this->put(
            route('api.stages.update', ['stage' => $stageInDB]),
            [
                'name' => $oldStageInDB->name,
                'pipeline_id' => $pipeline->id
            ]
        )
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }
}
