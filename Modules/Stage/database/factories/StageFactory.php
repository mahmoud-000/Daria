<?php

namespace Modules\Stage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Stage\Models\Stage;

class StageFactory extends Factory
{
    protected $model = Stage::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'complete' => $this->faker->numberBetween(10, 90),
            'pipeline_id' => null,
            'is_default' => false,
            'is_active' => true,
        ];
    }
}
