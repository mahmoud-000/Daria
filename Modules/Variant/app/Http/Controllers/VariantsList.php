<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Variant\Models\Variant;
use Modules\Variant\Transformers\VariantResource;

class VariantsList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return VariantResource::collection(
            Variant::query()
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
