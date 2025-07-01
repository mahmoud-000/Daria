<?php

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Payment;
use Modules\Quotation\Models\Quotation;
use Modules\Stock\Models\Stock;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class QuotationDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $warehouses = Warehouse::where('is_active', true)->get();
        // Quotation::factory(5)->create()->each(function ($quotation) use ($warehouses) {
        //     Payment::factory(1)->create(['quotation_id' => $quotation->id]);
        //     Variant::factory(1)->create(['quotation_id' => $quotation->id])->each(function ($variant) use ($warehouses, $quotation) {
        //         foreach ($warehouses as $warehouse) {
        //             Stock::factory(1)->create([
        //                 'warehouse_id' => $warehouse->id,
        //                 'quotation_id' => $quotation->id,
        //                 'variant_id' => $variant->id,
        //                 'quantity' => 0
        //             ]);
        //         }
        //     });
        // });
    }
}
