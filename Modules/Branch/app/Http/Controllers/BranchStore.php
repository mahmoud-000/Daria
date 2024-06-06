<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;

class BranchStore extends Controller
{
    public function __invoke($pipeline, $branches)
    {
        return $pipeline->branches()->createMany($branches);
    }
}
