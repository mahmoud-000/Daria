<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\PurchaseReturn\Transformers\PurchaseReturnsCollectionResource;
use Symfony\Component\HttpFoundation\Response;

class PurchaseReturnsList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-purchaseReturn', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return PurchaseReturnsCollectionResource::collection(
            PurchaseReturn::query()
                ->select(['id', 'date', 'warehouse_id', 'supplier_id', 'pipeline_id', 'paid_amount', 'payment_status', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouse:id,name', 'supplier:id,fullname', 'pipeline:id,name', 'payments:id,amount'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
