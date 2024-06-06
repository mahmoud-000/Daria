<?php

namespace Modules\SaleReturn\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SaleReturn\Entities\SaleReturn;
use Modules\Stock\Entities\Stock;
use Modules\Variant\Entities\Variant;
use Modules\Warehouse\Entities\Warehouse;

class SaleReturnDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        SaleReturn::factory(5)->create()->each(function ($sale_return) use ($warehouses) {
            Variant::factory(1)->create(['sale_return_id' => $sale_return->id])->each(function ($variant) use ($warehouses, $sale_return) {
                foreach ($warehouses as $warehouse) {
                    Stock::factory(1)->create([
                        'warehouse_id' => $warehouse->id,
                        'sale_return_id' => $sale_return->id,
                        'variant_id' => $variant->id,
                        'quantity' => 0
                    ]);
                }
            });
        });
    }
}
