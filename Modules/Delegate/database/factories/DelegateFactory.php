<?php

namespace Modules\Delegate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Delegate\Models\Delegate;
use Illuminate\Support\Str;

class DelegateFactory extends Factory
{
    protected $model = Delegate::class;

    public function definition()
    {
        return [
            'fullname' => $this->faker->userName(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'is_active' => $this->faker->boolean(),
            'remarks'  => $this->faker->paragraph(),
            'type' => $this->faker->randomElement([1, 2]),
            'commission_type' => $this->faker->randomElement([1, 2]),
            'commission' => $this->faker->numberBetween(10, 2000),
        ];
    }
}
