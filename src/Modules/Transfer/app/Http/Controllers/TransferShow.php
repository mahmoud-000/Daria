<?php

namespace Modules\Transfer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Transfer\Models\Transfer;
use Modules\Transfer\Transformers\TransferResource;

class TransferShow extends Controller
{
    public function __invoke(Transfer $transfer)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-transfer'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return TransferResource::make(
            $transfer->load(
                [
                    'media',
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
