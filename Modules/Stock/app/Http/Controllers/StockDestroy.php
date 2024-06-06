<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Stock\Models\Stock;

class StockDestroy extends Controller
{
    public function __invoke($item, $variantsIds)
    {
        Stock::where('item_id', $item->id)->whereIn('variant_id', $variantsIds)->delete();
    }
}
