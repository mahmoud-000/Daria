<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Transformers\SuppliersCollectionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class SuppliersList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::allows('list-supplier'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return SuppliersCollectionResource::collection(
            Supplier::query()
                ->select(['id', 'fullname', 'email', 'company_name', 'type', 'is_active', 'created_at', 'updated_at'])
                ->with(['media'])
                ->withCount(['media'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
