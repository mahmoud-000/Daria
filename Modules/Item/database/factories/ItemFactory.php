<?php

namespace Modules\Item\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Item\Models\Item;
use Modules\Unit\Models\Unit;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'label' => $this->faker->sentence(3),
            'cost' => $this->faker->numberBetween(10, 200),
            'price' => $this->faker->numberBetween(20, 400),
            'tax' => 0,
            'tax_type' => $this->faker->randomElement([1, 2]),
            'code' => $this->faker->randomNumber(8),
            'sku' => $this->faker->randomNumber(8),
            'barcode_type' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'unit_id' => function () {
                return Unit::where('unit_id', null)->where('is_active', true)->get()->first()->id;
            },
            'purchase_unit_id' => function () {
                return Unit::where('unit_id', null)->where('is_active', true)->get()->first()->id;
            },
            'sale_unit_id' => function () {
                return Unit::where('unit_id', null)->where('is_active', true)->get()->first()->id;
            },

            'type' => $this->faker->unique->randomElement([1, 2, 3]),
            'product_type' => $this->faker->randomElement([1, 2]),
            'is_active' => true,
            'is_available_for_purchase' => true,
            'is_available_for_sale' => true,
            'is_available_for_edit_in_purchase' => true,
            'is_available_for_edit_in_sale' => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
