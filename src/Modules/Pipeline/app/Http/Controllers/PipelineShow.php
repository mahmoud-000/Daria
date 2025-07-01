<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Transformers\PipelineResource;

class PipelineShow extends Controller
{
    public function __invoke(Pipeline $pipeline)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-pipeline'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return PipelineResource::make($pipeline->load('stages'));
    }
}
