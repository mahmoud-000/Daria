<?php

namespace Modules\Variant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Variant\Models\Variant;
use Modules\Item\Models\Item;

class VariantFactory extends Factory
{
    protected $model = Variant::class;

    public function definition()
    {
        $itemIds = Item::where('is_active', true)->pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence(2),
            'cost' => $this->faker->numberBetween(10, 500),
            'price' => $this->faker->numberBetween(100, 1000),
            'code' => $this->faker->randomNumber(8),
            'color' => $this->faker->hexColor(),
            'item_id' => $this->faker->randomElement($itemIds),
            'is_active' => true,
        ];
    }
}
