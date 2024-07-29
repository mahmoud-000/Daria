<?php

namespace Modules\Transfer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Transfer\Models\Transfer;
use Modules\Transfer\Transformers\TransfersCollectionResource;
use Symfony\Component\HttpFoundation\Response;

class TransfersList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-transfer', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return TransfersCollectionResource::collection(
            Transfer::query()
                ->select(['id', 'date', 'from_warehouse_id', 'to_warehouse_id', 'pipeline_id', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouseFrom:id,name', 'warehouseTo:id,name', 'pipeline:id,name'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
