<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\ِAccountType\Models\ِAccountType;
use Modules\ِAccountType\Transformers\ِAccountTypeResource;
use Symfony\Component\HttpFoundation\Response;

class ِAccountTypesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-ِaccountType', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return ِAccountTypeResource::collection(ِAccountType::search($req->filter)
        ->orderBy($req->sortBy, $dir)
        ->paginate($req->rowsPerPage));
    }
}
