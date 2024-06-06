<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Stage\Models\Stage;
use Modules\Stage\Transformers\StageResource;
use Symfony\Component\HttpFoundation\Response;

class StagesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-stage', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return StageResource::collection(Stage::search($req->filter)
        ->orderBy($req->sortBy, $dir)
        ->paginate($req->rowsPerPage));
    }
}
