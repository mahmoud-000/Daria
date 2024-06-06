<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Item\Models\Item;
use Modules\Item\Transformers\ItemResource;

class ItemShow extends Controller
{
    public function __invoke(Item $item)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-item'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return ItemResource::make($item->load(['media', 'variants', 'stock']));
    }
}
