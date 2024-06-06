<?php

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'fullname' => $this->faker->userName(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'is_active' => $this->faker->boolean(),
            'type' => $this->faker->randomElement([1,2]),
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
