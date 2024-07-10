<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Transformers\QuotationResource;

class QuotationShow extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-quotation'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return QuotationResource::make(
            $quotation->load(
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
