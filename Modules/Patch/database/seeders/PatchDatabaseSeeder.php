<?php

namespace Modules\Patch\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Patch\Models\Patch;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class PatchDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::get();
        Patch::factory(1)->create()->each(function ($patch) use ($warehouses) {
            Variant::factory(1)->create(['patch_id' => $patch->id])->each(function ($patch_variant) use ($warehouses, $patch) {
                // foreach ($warehouses as $warehouse) {
                //     PatchWarehouse::factory(1)->create([
                //         'warehouse_id' => $warehouse->id,
                //         'patch_id' => $patch->id,
                //         'patch_variant_id' => $patch_variant->id,
                //         'quantity' => 0
                //     ]);
                // }
            });
        });
    }
}
