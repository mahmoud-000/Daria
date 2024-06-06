<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Entities\PurchaseReturn;
use Modules\PurchaseReturn\Transformers\PurchaseReturnResource;

class PurchaseReturnShow extends Controller
{
    public function __invoke(PurchaseReturn $purchase_return)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-purchase_return'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return PurchaseReturnResource::make($purchase_return->load(['media', 'variants', 'stock']));
    }
}
