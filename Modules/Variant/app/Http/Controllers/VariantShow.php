<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Variant\Models\Variant;
use Modules\Variant\Transformers\VariantResource;

class VariantShow extends Controller
{
    public function __invoke(Variant $variant)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-variant'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return VariantResource::make($variant->load(['item', 'item.media']));
    }
}
