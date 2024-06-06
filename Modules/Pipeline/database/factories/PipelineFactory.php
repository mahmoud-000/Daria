<?php

namespace Modules\Pipeline\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pipeline\Models\Pipeline;

class PipelineFactory extends Factory
{
    protected $model = Pipeline::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'module_name' => $this->faker->randomElement(['purchase', 'purchase_return', 'sale', 'sale_return']),
            'is_active' => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
