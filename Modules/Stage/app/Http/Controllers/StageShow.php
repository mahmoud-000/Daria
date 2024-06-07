<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Stage\Models\Stage;
use Modules\Stage\Transformers\StageResource;

class StageShow extends Controller
{
    public function __invoke(Stage $stage)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-stage'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return StageResource::make($stage->load('pipeine'));
    }
}
