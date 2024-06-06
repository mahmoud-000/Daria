<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Entities\SaleReturn;
use Modules\SaleReturn\Transformers\SaleReturnResource;

class SaleReturnShow extends Controller
{
    public function __invoke(SaleReturn $sale_return)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-sale_return'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return SaleReturnResource::make($sale_return->load(['media', 'variants', 'stock']));
    }
}
