<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Transformers\WarehouseResource;
use Symfony\Component\HttpFoundation\Response;

class WarehousesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-warehouse', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return WarehouseResource::collection(Warehouse::search($req->filter)
        ->orderBy($req->sortBy, $dir)
        ->paginate($req->rowsPerPage));
    }
}
