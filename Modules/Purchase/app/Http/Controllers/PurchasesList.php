<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Transformers\PurchasesCollectionResource;

class PurchasesList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return PurchasesCollectionResource::collection(
            Purchase::query()
                ->select(['id', 'date', 'warehouse_id', 'supplier_id', 'pipeline_id', 'paid_amount', 'payment_status', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouse:id,name', 'supplier:id,fullname', 'pipeline:id,name', 'payments:id,amount'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
