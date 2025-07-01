<?php

namespace Modules\Patch\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Patch\Models\Patch;

class PatchDestroy extends Controller
{
    public function __invoke($item, $variantsIds)
    {
        Patch::where('item_id', $item->id)->whereIn('variant_id', $variantsIds)->delete();
    }
}
