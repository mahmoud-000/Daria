<?php

namespace Modules\SaleReturn\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\SaleReturn\Entities\SaleReturn;
use Illuminate\Support\Str;

class SaleReturnFactory extends Factory
{
    protected $model = SaleReturn::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'label' => $this->faker->sentence(3),
            'currency' => $this->faker->randomElement([config('setting.currency'), 'EUR', 'EGP']),
            'cost' => $this->faker->numberBetween(10, 500),
            'price' => $this->faker->numberBetween(100, 1000),
            'tax' => $this->faker->numberBetween(1, 50),
            'tax_type' => $this->faker->randomElement([1, 2]),
            'barcode' => $this->faker->sentence(2),
            'barcode_type' => $this->faker->randomElement([1, 2, 3, 4, 5]),

            'sale_return_type' => $this->faker->randomElement([1, 2, 3]),
            'is_active' => $this->faker->boolean(),
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
