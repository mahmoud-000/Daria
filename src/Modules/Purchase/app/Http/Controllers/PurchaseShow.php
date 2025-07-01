<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Transformers\PurchaseResource;

class PurchaseShow extends Controller
{
    public function __invoke(Purchase $purchase)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-purchase'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return PurchaseResource::make(
            $purchase->load(
                [
                    'media',
                    'supplier',
                    'supplier.media',
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
