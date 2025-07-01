<?php

namespace Modules\Purchase\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\Purchase\Models\Purchase;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class PurchaseDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // Purchase::factory(5)->create()->each(function ($purchase) use ($warehouses) {
        //     Payment::factory(1)->create(['purchase_id' => $purchase->id]);
        //     Variant::factory(1)->create(['purchase_id' => $purchase->id])->each(function ($variant) use ($warehouses, $purchase) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'purchase_id' => $purchase->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
