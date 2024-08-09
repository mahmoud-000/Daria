<?php

namespace Modules\Company\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Transformers\CompanyResource;
use Modules\Company\Models\Company;

class CompanyOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return CompanyResource::collection(
            Company::query()
            ->with('media')
            ->where('is_active', ActiveEnum::ACTIVED->value)
            ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10));
    }
}
