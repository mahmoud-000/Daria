<?php

namespace Modules\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\Sale\Models\Sale;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class SaleDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // Sale::factory(5)->create()->each(function ($sale) use ($warehouses) {
        //     Payment::factory(1)->create(['sale_id' => $sale->id]);
        //     Variant::factory(1)->create(['sale_id' => $sale->id])->each(function ($variant) use ($warehouses, $sale) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'sale_id' => $sale->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
