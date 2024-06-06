<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Entities\SaleReturn;
use Modules\SaleReturn\Transformers\SaleReturnResource;

class SaleReturnsList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return SaleReturnResource::collection(SaleReturn::with(['media', 'category:id,name', 'brand:id,name', 'sale_returnUnit:id,short_name', 'saleUnit:id,short_name'])->search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
