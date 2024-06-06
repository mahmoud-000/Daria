<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class StockDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::get();
        Stock::factory(1)->create()->each(function ($stock) use ($warehouses) {
            Variant::factory(1)->create(['stock_id' => $stock->id])->each(function ($stock_variant) use ($warehouses, $stock) {
                // foreach ($warehouses as $warehouse) {
                //     StockWarehouse::factory(1)->create([
                //         'warehouse_id' => $warehouse->id,
                //         'stock_id' => $stock->id,
                //         'stock_variant_id' => $stock_variant->id,
                //         'quantity' => 0
                //     ]);
                // }
            });
        });
    }
}
