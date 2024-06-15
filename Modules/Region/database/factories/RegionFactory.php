<?php

namespace Modules\Region\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Region\Models\Region;
use Illuminate\Support\Str;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'is_active' => $this->faker->boolean(),
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
