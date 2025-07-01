<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Brand\Http\Controllers\BrandOptions;
use Modules\Category\Http\Controllers\CategoryOptions;
use Modules\Unit\Http\Controllers\UnitOptions;

class ItemFormOptions extends Controller
{
    public function __invoke(Request $request)
    {
        return [
            'categories' => (new CategoryOptions)($request),
            'brands' => (new BrandOptions)($request),
            'units' => (new UnitOptions)(),
        ];
    }
}
