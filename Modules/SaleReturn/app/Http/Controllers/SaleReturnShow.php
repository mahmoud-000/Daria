<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\SaleReturn\Transformers\SaleReturnResource;

class SaleReturnShow extends Controller
{
    public function __invoke(SaleReturn $saleReturn)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-saleReturn'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return SaleReturnResource::make(
            $saleReturn->load(
                [
                    'media',
                    'customer',
                    'customer.media',
                    'delegate',
                    'delegate.media',
                    'details',
                    'details.detailable',
                    'details.item',
                    'details.variant',
                    'details.stock'
                ]
            )
        );
    }
}
