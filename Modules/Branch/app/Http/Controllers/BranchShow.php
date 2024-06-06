<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Branch\Models\Branch;
use Modules\Branch\Transformers\BranchResource;

class BranchShow extends Controller
{
    public function __invoke(Branch $branch)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-branch'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return BranchResource::make($branch);
    }
}
