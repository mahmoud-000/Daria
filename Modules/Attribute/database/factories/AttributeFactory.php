<?php

namespace Modules\Attribute\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Attribute\Models\Attribute;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'is_active' => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
