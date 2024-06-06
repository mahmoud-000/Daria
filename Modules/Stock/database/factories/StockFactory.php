<?php

namespace Modules\Stock\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Stock\Models\Stock;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition()
    {
        return [
            'warehouse_id' => null,
            'item_id' => null,
            'variant_id' => null,
            'quantity' => 0,
        ];
    }
}
