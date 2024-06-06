<?php

namespace Modules\Supplier\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Supplier\Models\Supplier;
use Illuminate\Support\Str;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

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
