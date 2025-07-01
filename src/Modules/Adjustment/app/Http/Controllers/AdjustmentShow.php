<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Adjustment\Models\Adjustment;
use Modules\Adjustment\Transformers\AdjustmentResource;

class AdjustmentShow extends Controller
{
    public function __invoke(Adjustment $adjustment)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-adjustment'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));

        return AdjustmentResource::make(
            $adjustment->load(
                [
                    'media',
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
