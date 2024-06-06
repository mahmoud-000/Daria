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
            'default' => false,
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
                'default' => false,
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
}
