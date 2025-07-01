<?php

namespace Modules\Detail\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Item\Models\Item;
use Modules\Purchase\Models\Purchase;
use Modules\Unit\Models\Unit;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class DetailFactory extends Factory
{
    protected $model = \Modules\Detail\Models\Detail::class;

    public function definition()
    {
        return [
            'detailable_id' => function () {
                return Purchase::factory()->create()->id;
            },
            'detailable_type' => 'Purchase',
            'amount' => 0,
            'tax' => 0,
            'tax_type' => 1,
            'discount' => 0,
            'discount_type' => 1,
            'unit_id' => function () {
                return Unit::factory()->create()->id;
            },
            'warehouse_id' => function () {
                return Warehouse::factory()->create()->id;
            },
            'item_id' => function () {
                return Item::factory()->create()->id;
            },
            'variant_id' => function () {
                return Variant::factory()->create()->id;
            },
            'total' => 0,
            'quantity' => 0,
        ];
    }
}
