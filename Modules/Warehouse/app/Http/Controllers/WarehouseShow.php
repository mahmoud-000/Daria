<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Transformers\WarehouseResource;

class WarehouseShow extends Controller
{
    public function __invoke(Warehouse $warehouse)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-warehouse'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return WarehouseResource::make($warehouse);
    }
}
