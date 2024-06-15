<?php

namespace Modules\Job\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobsTest extends TestCase
{
    use RefreshDatabase;
    public $job;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->job = $this->createJob();
        $this->createOwner();    
    }

    public function test_can_list_jobs()
    {
        $res = $this->get(route('api.jobs.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_job_with_required_inputs()
    {
        $res = $this->post(route('api.jobs.store'), [
            'name' => 'testjobname',
            'is_active' => true,
        ])->json();

        
        $this->assertDatabaseCount('jobs', 2);
        $this->assertDatabaseHas('jobs', [
            'name' => 'testjobname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testjobname', 'module' => __('modules.job')]));
    }

    public function test_can_edit_job()
    {
        $res = $this->put(
            route('api.jobs.update', ['job' => $this->job]),
            [
                'name' => 'newjobname',
                'is_active' => true,
            ]
        )->json();

        $this->assertDatabaseCount('jobs', 1);
        $this->assertDatabaseHas('jobs', [
            'name' => 'newjobname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newjobname', 'module' => __('modules.job')]));
    }

    public function test_can_show_job()
    {
        $jobId = $this->job->id;
        $res = $this->get(route('api.jobs.show', ['job' => $jobId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($jobId, $res['data']['id']);
    }

    public function test_can_delete_job()
    {
        $res = $this->delete(route('api.jobs.destroy', ['job' => $this->job->id]))->json();
        $this->assertTrue($res['success']);
    }
}
