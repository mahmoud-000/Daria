<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;

class StageStore extends Controller
{
    public function __invoke($pipeline, $stages)
    {
        return $pipeline->stages()->createMany($stages);
    }
}
