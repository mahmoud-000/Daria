<?php

namespace Modules\Adjustment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pipeline\Models\Pipeline;
use Modules\Adjustment\Models\Adjustment;
use Modules\Warehouse\Models\Warehouse;

class AdjustmentFactory extends Factory
{
    protected $model = Adjustment::class;

    public function definition()
    {
        $pipelineId = Pipeline::factory()->create()->id;
        return [
            'date' => $this->faker->date(),
            'warehouse_id' => function () {
                return Warehouse::factory()->create()->id;
            },
            'items' => 0,
            'grand_total' => 0,
            'remarks'  => $this->faker->paragraph(),
            'effected'  => true,
        ];
    }
}
