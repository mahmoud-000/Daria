<?php

namespace Modules\PurchaseReturn\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class PurchaseReturnDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // PurchaseReturn::factory(5)->create()->each(function ($purchaseReturn) use ($warehouses) {
        //     Payment::factory(1)->create(['purchaseReturn_id' => $purchaseReturn->id]);
        //     Variant::factory(1)->create(['purchaseReturn_id' => $purchaseReturn->id])->each(function ($variant) use ($warehouses, $purchaseReturn) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'purchaseReturn_id' => $purchaseReturn->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
