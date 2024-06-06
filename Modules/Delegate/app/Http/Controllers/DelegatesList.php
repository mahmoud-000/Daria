<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Delegate\Models\Delegate;
use Modules\Delegate\Transformers\DelegateResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class DelegatesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::allows('list-delegate'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return DelegateResource::collection(
            Delegate::query()
                ->with(['media'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
