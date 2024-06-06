<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Company\Transformers\CompanyResource;
use Modules\Company\Models\Company;

class CompanyOptions extends Controller
{
    public function __invoke($moduleName)
    {
        return CompanyResource::collection(Company::with('branches')
            ->where('is_active', true)
            ->where('module_name', $moduleName)
            ->get());
    }
}
