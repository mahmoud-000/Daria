<?php

namespace Modules\Transfer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Delegate\Models\Delegate;
use Modules\Pipeline\Models\Pipeline;
use Modules\Transfer\Models\Transfer;
use Modules\Stage\Models\Stage;
use Modules\Warehouse\Models\Warehouse;

class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition()
    {
        $pipelineId = Pipeline::factory()->create()->id;
        return [
            'date' => $this->faker->date(),
            'from_warehouse_id' => function () {
                return Warehouse::factory()->create()->id;
            },
            'to_warehouse_id' => function () {
                return Warehouse::factory()->create()->id;
            },
            'pipeline_id' => $pipelineId,
            'stage_id' => function () use ($pipelineId) {
                return Stage::factory()->create(['pipeline_id' => $pipelineId, 'complete' => 100, 'is_active' => true])->id;
            },
            'delegate_id' => function () {
                return Delegate::factory()->create()->id;
            },
            'tax' => 0,
            'discount' => 0,
            'shipping' => 0,
            'other_expenses' => 0,
            'grand_total' => 0,
            'effected'  => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
