<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Transformers\SaleResource;

class SalesList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return SaleResource::collection(Sale::with(['media', 'category:id,name', 'brand:id,name', 'saleUnit:id,short_name', 'saleUnit:id,short_name'])->search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
