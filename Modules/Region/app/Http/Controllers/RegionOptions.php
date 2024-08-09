<?php

namespace Modules\Region\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Region\Transformers\RegionResource;
use Modules\Region\Models\Region;

class RegionOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return RegionResource::collection(
            Region::query()
            ->with('media')
            ->where('is_active', ActiveEnum::ACTIVED->value)
            ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10));
    }
}
