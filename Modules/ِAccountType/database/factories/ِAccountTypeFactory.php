<?php

namespace Modules\ِAccountType\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ِAccountType\Models\ِAccountType;

class ِAccountTypeFactory extends Factory
{
    protected $model = ِAccountType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'is_active' => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
