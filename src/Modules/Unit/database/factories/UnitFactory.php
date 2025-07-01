<?php

namespace Modules\Unit\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Unit\Models\Unit;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Piece', 'Kilogram', 'Meter']),
            'short_name' => $this->faker->randomElement(['pc', 'kg', 'm']),
            'operator' => $this->faker->randomElement(['/', '*']),
            'operator_value' => $this->faker->randomElement([1, 1000]),
            'unit_id' => null,
            'is_active' => true,
        ];
    }
}
