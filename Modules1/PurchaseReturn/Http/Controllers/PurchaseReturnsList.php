<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Entities\PurchaseReturn;
use Modules\PurchaseReturn\Transformers\PurchaseReturnResource;

class PurchaseReturnsList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return PurchaseReturnResource::collection(PurchaseReturn::with(['media', 'category:id,name', 'brand:id,name', 'purchase_returnUnit:id,short_name', 'saleUnit:id,short_name'])->search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
