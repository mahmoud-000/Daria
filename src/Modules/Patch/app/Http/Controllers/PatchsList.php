<?php

namespace Modules\Patch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Patch\Models\Patch;
use Modules\Patch\Transformers\PatchResource;

class PatchsList extends Controller
{
    public function __invoke(Request $req)
    {
        return PatchResource::collection(Patch::get());
    }
}
