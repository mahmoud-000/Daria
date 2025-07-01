<?php

namespace Modules\Transfer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\Transfer\Models\Transfer;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class TransferDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // Transfer::factory(5)->create()->each(function ($transfer) use ($warehouses) {
        //     Payment::factory(1)->create(['transfer_id' => $transfer->id]);
        //     Variant::factory(1)->create(['transfer_id' => $transfer->id])->each(function ($variant) use ($warehouses, $transfer) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'transfer_id' => $transfer->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
