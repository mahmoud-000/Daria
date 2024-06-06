<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Branch\Models\Branch;
use Modules\Branch\Transformers\BranchResource;
use Symfony\Component\HttpFoundation\Response;

class BranchesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-branch', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return BranchResource::collection(Branch::search($req->filter)
        ->orderBy($req->sortBy, $dir)
        ->paginate($req->rowsPerPage));
    }
}
