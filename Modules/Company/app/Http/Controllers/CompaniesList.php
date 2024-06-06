<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Models\Company;
use Modules\Company\Transformers\CompanyResource;

class CompaniesList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return CompanyResource::collection(
            Company::query()
            ->with('media')
            ->search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
