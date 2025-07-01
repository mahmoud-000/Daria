<?php

namespace Modules\SaleReturn\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class SaleReturnDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // SaleReturn::factory(5)->create()->each(function ($saleReturn) use ($warehouses) {
        //     Payment::factory(1)->create(['saleReturn_id' => $saleReturn->id]);
        //     Variant::factory(1)->create(['saleReturn_id' => $saleReturn->id])->each(function ($variant) use ($warehouses, $saleReturn) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'saleReturn_id' => $saleReturn->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
