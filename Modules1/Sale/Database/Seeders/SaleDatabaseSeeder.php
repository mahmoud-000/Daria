<?php

namespace Modules\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sale\Entities\Sale;
use Modules\Stock\Entities\Stock;
use Modules\Variant\Entities\Variant;
use Modules\Warehouse\Entities\Warehouse;

class SaleDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        Sale::factory(5)->create()->each(function ($sale) use ($warehouses) {
            Variant::factory(1)->create(['sale_id' => $sale->id])->each(function ($variant) use ($warehouses, $sale) {
                foreach ($warehouses as $warehouse) {
                    Stock::factory(1)->create([
                        'warehouse_id' => $warehouse->id,
                        'sale_id' => $sale->id,
                        'variant_id' => $variant->id,
                        'quantity' => 0
                    ]);
                }
            });
        });
    }
}
