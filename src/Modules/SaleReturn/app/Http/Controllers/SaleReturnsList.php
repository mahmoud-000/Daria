<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\SaleReturn\Transformers\SaleReturnsCollectionResource;
use Symfony\Component\HttpFoundation\Response;

class SaleReturnsList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-saleReturn', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return SaleReturnsCollectionResource::collection(
            SaleReturn::query()
                ->select(['id', 'date', 'warehouse_id', 'customer_id', 'pipeline_id', 'paid_amount', 'payment_status', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouse:id,name', 'customer:id,fullname', 'pipeline:id,name', 'payments:id,amount'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
