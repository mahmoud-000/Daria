<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Adjustment\Models\Adjustment;
use Modules\Adjustment\Transformers\AdjustmentsCollectionResource;
use Symfony\Component\HttpFoundation\Response;

class AdjustmentsList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-adjustment', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return AdjustmentsCollectionResource::collection(
            Adjustment::query()
                ->select(['id', 'date', 'warehouse_id', 'items', 'pipeline_id', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouse:id,name', 'pipeline:id,name'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
