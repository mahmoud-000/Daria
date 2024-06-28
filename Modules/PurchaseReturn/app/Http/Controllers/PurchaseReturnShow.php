<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\PurchaseReturn\Transformers\PurchaseReturnResource;

class PurchaseReturnShow extends Controller
{
    public function __invoke(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-purchaseReturn'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return PurchaseReturnResource::make(
            $purchaseReturn->load(
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
