<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Warehouse\Models\Warehouse;

class WarehouseOptions extends Controller
{
    public function __invoke()
    {
        return WarehouseResource::collection(Warehouse::where('is_active', true)->get());
    }
}
