<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Entities\Quotation;
use Modules\Quotation\Transformers\QuotationResource;

class QuotationShow extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-quotation'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return QuotationResource::make($quotation->load(['media', 'variants', 'stock']));
    }
}
