<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Transformers\PipelineResource;
use Symfony\Component\HttpFoundation\Response;

class PipelinesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-pipeline', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return PipelineResource::collection(Pipeline::search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
