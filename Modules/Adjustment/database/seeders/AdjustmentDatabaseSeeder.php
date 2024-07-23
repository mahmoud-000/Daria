<?php

namespace Modules\Adjustment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\Adjustment\Models\Adjustment;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class AdjustmentDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // Adjustment::factory(5)->create()->each(function ($adjustment) use ($warehouses) {
        //     Payment::factory(1)->create(['adjustment_id' => $adjustment->id]);
        //     Variant::factory(1)->create(['adjustment_id' => $adjustment->id])->each(function ($variant) use ($warehouses, $adjustment) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'adjustment_id' => $adjustment->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
