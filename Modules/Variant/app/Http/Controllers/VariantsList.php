<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Variant\Models\Variant;
use Modules\Variant\Transformers\VariantResource;
use Symfony\Component\HttpFoundation\Response;

class VariantsList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-variant', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return VariantResource::collection(
            Variant::query()
                ->with(['item', 'item.media'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
