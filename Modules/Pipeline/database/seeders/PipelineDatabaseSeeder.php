<?php

namespace Modules\Pipeline\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Models\Stage;

class PipelineDatabaseSeeder extends Seeder
{
    public function run()
    {
        Pipeline::factory(5)->create()->each(function($pipe) {
            Stage::factory()->create(['pipeline_id' => $pipe->id, 'name' => 'New', 'complete' => 20, 'is_default' => true]);
            Stage::factory()->create(['pipeline_id' => $pipe->id, 'name' => 'Pending', 'complete' => 50, 'is_default' => false]);
            Stage::factory()->create(['pipeline_id' => $pipe->id, 'name' => 'Review', 'complete' => 80, 'is_default' => false]);
            Stage::factory()->create(['pipeline_id' => $pipe->id, 'name' => 'Complete', 'complete' => 100, 'is_default' => true]);
        });
    }
}
