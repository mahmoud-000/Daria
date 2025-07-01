<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Stock\Models\Stock;
use Modules\Stock\Transformers\StockResource;

class StocksList extends Controller
{
    public function __invoke(Request $req)
    {
        return StockResource::collection(Stock::get());
    }
}
