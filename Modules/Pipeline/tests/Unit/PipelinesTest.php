<?php

namespace Modules\Pipeline\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class PipelinesTest extends TestCase
{
    use RefreshDatabase;
    public $pipeline;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->pipeline = $this->createPipeline();
        $this->createOwner();
    }

    public function test_can_list_pipelines()
    {
        $res = $this->get(route('api.pipelines.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_pipeline_without_stages()
    {
        $res = $this->post(route('api.pipelines.store'), [
            'name' => 'testpipelinename',
            'app_name' => 'Quotation',
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('stages', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_create_pipeline_with_stages()
    {
        $pipelineId = $this->pipeline->id;
        $stages = [
            $this->createStage(['pipeline_id' => $pipelineId])->toArray()
        ];

        $res = $this->post(route('api.pipelines.store'), [
            'name' => 'testpipelinename',
            'app_name' => 'Quotation',
            'stages' => $stages
        ])->json();

        $this->assertDatabaseCount('pipelines', 2);
        $this->assertDatabaseHas('pipelines', [
            'name' => 'testpipelinename',
            'app_name' => 'Quotation',
        ]);

        $this->assertDatabaseCount('stages', 3);
        $this->assertDatabaseHas('stages', ['name' => 'New']);

        $this->assertDatabaseHas('pipelines', ['name' => 'testpipelinename']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testpipelinename', 'module' => __('modules.pipeline')]));
    }

    public function test_can_edit_pipeline_and_stages()
    {
        $pipeline = $this->pipeline;
        $pipelineId = $pipeline->id;
        $stage_1 = $this->storeStage(['name' => 'Stage 1', 'color' => 'green', 'pipeline_id' => $pipelineId])->toArray();
        $stage_1['name'] = 'Update Stage 1';

        $allStages = [
            $stage_1,
        ];

        $res = $this->put(
            route('api.pipelines.update', ['pipeline' => $pipelineId]),
            [
                'name' => 'newpipelinename',
                'app_name' => 'Adjustment',
                'stages' => $allStages
            ]
        )->json();

        $this->assertDatabaseCount('pipelines', 1);
        $this->assertDatabaseHas('pipelines', [
            'name' => 'newpipelinename',
            'app_name' => 'Adjustment',
        ]);

        $this->assertDatabaseCount('stages', 3);
        $this->assertDatabaseHas('stages', ['name' => 'New', 'pipeline_id' => $pipelineId]);
        $this->assertDatabaseHas('stages', ['name' => 'Complete', 'pipeline_id' => $pipelineId]);
        $this->assertDatabaseHas('stages', ['name' => 'Update Stage 1', 'pipeline_id' => $pipelineId]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newpipelinename', 'module' => __('modules.pipeline')]));
    }

    public function test_can_edit_pipeline_and_add_a_new_stages()
    {
        $pipeline = $this->pipeline;
        $pipelineId = $pipeline->id;
        $stage_1 = $this->createStage(['name' => 'Stage 1', 'color' => 'green', 'pipeline_id' => $pipelineId])->toArray();
        $stage_2 = $this->createStage(['name' => 'Stage 2', 'color' => 'red', 'pipeline_id' => $pipelineId])->toArray();

        $oldStages = [
            $stage_1,
        ];

        $pipeline->stages()->createMany($oldStages);
        $pipelineStages = $pipeline->stages->toArray();
        $allStages = [
            ...$pipelineStages,
            $stage_2,
        ];

        $res = $this->put(
            route('api.pipelines.update', ['pipeline' => $pipelineId]),
            [
                'name' => 'newpipelinename',
                'app_name' => 'Adjustment',
                'stages' => $allStages
            ]
        )->json();

        $this->assertDatabaseCount('pipelines', 1);
        $this->assertDatabaseHas('pipelines', [
            'name' => 'newpipelinename',
            'app_name' => 'Adjustment',
        ]);

        $this->assertDatabaseCount('stages', 4);
        $this->assertDatabaseHas('stages', ['name' => 'New', 'pipeline_id' => $pipelineId]);
        $this->assertDatabaseHas('stages', ['name' => 'Complete', 'pipeline_id' => $pipelineId]);
        $this->assertDatabaseHas('stages', ['name' => 'Stage 1', 'pipeline_id' => $pipelineId]);
        $this->assertDatabaseHas('stages', ['name' => 'Stage 2', 'pipeline_id' => $pipelineId]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newpipelinename', 'module' => __('modules.pipeline')]));
    }

    public function test_can_show_pipeline()
    {
        $randomPipelineId = $this->pipeline->id;
        $res = $this->get(route('api.pipelines.show', ['pipeline' => $randomPipelineId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($randomPipelineId, $res['data']['id']);
    }

    public function test_can_delete_pipeline()
    {
        $res = $this->delete(route('api.pipelines.destroy', ['pipeline' => $this->pipeline->id]))->json();
        $this->assertTrue($res['success']);
    }
}
