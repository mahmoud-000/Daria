<?php

namespace Modules\Branch\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Branch\Transformers\BranchResource;
use Modules\Branch\Models\Branch;

class BranchOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return BranchResource::collection(
            Branch::query()
            ->where('is_active', ActiveEnum::ACTIVED->value)
            ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10));
    }
}
