<?php

namespace Modules\Brand\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Brand\Transformers\BrandResource;
use Modules\Brand\Models\Brand;

class BrandOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return BrandResource::collection(
            Brand::query()
            ->with('media')
            ->where('is_active', ActiveEnum::ACTIVED->value)
            ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10));
    }
}
