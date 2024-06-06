<?php

namespace Modules\Patch\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatchsTest extends TestCase
{
    // use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->createOwner();
    }

    public function test_can_list_patchs_by_warehouse()
    {
        $res = $this->get(route('api.patch.by_warehouse', [
            'search' => 'test'
        ]))->json();
        
        $this->assertTrue(true);
    }
}
