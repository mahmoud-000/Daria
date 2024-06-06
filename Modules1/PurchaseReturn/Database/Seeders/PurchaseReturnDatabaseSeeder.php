<?php

namespace Modules\PurchaseReturn\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PurchaseReturn\Entities\PurchaseReturn;
use Modules\Stock\Entities\Stock;
use Modules\Variant\Entities\Variant;
use Modules\Warehouse\Entities\Warehouse;

class PurchaseReturnDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        PurchaseReturn::factory(5)->create()->each(function ($purchase_return) use ($warehouses) {
            Variant::factory(1)->create(['purchase_return_id' => $purchase_return->id])->each(function ($variant) use ($warehouses, $purchase_return) {
                foreach ($warehouses as $warehouse) {
                    Stock::factory(1)->create([
                        'warehouse_id' => $warehouse->id,
                        'purchase_return_id' => $purchase_return->id,
                        'variant_id' => $variant->id,
                        'quantity' => 0
                    ]);
                }
            });
        });
    }
}
