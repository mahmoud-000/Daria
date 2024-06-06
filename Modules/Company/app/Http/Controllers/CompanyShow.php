<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Company\Models\Company;
use Modules\Company\Transformers\CompanyResource;

class CompanyShow extends Controller
{
    public function __invoke(Company $company)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-company'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return CompanyResource::make($company->load('media', 'branches'));
    }
}
