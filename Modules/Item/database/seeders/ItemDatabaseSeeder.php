<?php

namespace Modules\Item\Database\Seeders;

use App\Enums\ItemTypesEnum;
use Illuminate\Database\Seeder;
use Modules\Item\Models\Item;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class ItemDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::where('deleted_at', null)
            ->pluck('id')
            ->toArray();

        Item::factory(30)->create()->each(function ($item) use ($warehouses) {
            if ($item->type === ItemTypesEnum::VARIABLE) {
                Variant::factory(5)->create(['item_id' => $item->id])->each(function ($variant) use ($warehouses, $item) {
                    foreach ($warehouses as $warehouse) {
                        Stock::factory(1)->create([
                            'warehouse_id' => $warehouse,
                            'item_id' => $item->id,
                            'variant_id' => $variant->id,
                            'quantity' => 0
                        ]);
                    }
                });
            } else {
                foreach ($warehouses as $warehouse) {
                    Stock::factory(1)->create([
                        'warehouse_id' => $warehouse,
                        'item_id' => $item->id,
                        'variant_id' => null,
                        'quantity' => 0
                    ]);
                }
            }
        });
    }
}
