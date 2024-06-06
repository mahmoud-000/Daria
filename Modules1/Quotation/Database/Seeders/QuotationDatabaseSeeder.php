<?php

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Entities\Quotation;
use Modules\Stock\Entities\Stock;
use Modules\Variant\Entities\Variant;
use Modules\Warehouse\Entities\Warehouse;

class QuotationDatabaseSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        Quotation::factory(5)->create()->each(function ($quotation) use ($warehouses) {
            Variant::factory(1)->create(['quotation_id' => $quotation->id])->each(function ($variant) use ($warehouses, $quotation) {
                foreach ($warehouses as $warehouse) {
                    Stock::factory(1)->create([
                        'warehouse_id' => $warehouse->id,
                        'quotation_id' => $quotation->id,
                        'variant_id' => $variant->id,
                        'quantity' => 0
                    ]);
                }
            });
        });
    }
}
