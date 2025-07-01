<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Unit\Transformers\UnitResource;
use Modules\Unit\Models\Unit;

class UnitOptions extends Controller
{
    public function __invoke()
    {
        return UnitResource::collection(Unit::where('is_active', true)->get());
    }
}
