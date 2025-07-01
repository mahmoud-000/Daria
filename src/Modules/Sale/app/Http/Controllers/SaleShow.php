<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Sale\Models\Sale;
use Modules\Sale\Transformers\SaleResource;

class SaleShow extends Controller
{
    public function __invoke(Sale $sale)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-sale'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return SaleResource::make(
            $sale->load(
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
