<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Http\Controllers\StockDestroy;

class VariantDestroy extends Controller
{
    public function __invoke($item)
    {
        DB::transaction(function () use ($item) {
            // Find Variant Ids to delete except the default one
            $variantsIds = collect($item->variants)->where('default', '!=', true)->pluck('id')->toArray();
            // Delete the stock for the variants
            (new StockDestroy)($variantsIds);
            // Delete the variants from the item
            $item->variants()->whereIn('id', $variantsIds)->delete();
        });
    }
}
