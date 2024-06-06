<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;

class VariantStore extends Controller
{
    public function __invoke($item, $variants)
    {
        return $item->variants()->createMany($variants);
    }
}
