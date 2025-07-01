<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Unit\Models\Unit;
use Modules\Unit\Transformers\UnitResource;

class UnitShow extends Controller
{
    public function __invoke(Unit $unit)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-unit'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return UnitResource::make($unit);
    }
}
