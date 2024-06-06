<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Item\Models\Item;
use Symfony\Component\HttpFoundation\Response;
use Modules\Item\Transformers\ItemsCollectionResource;

class ItemsList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-item', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return ItemsCollectionResource::collection(
            Item::query()
                ->select(['id', 'code', 'cost', 'price', 'unit_id',  'name', 'type', 'category_id', 'brand_id', 'is_active', 'is_available_for_purchase', 'is_available_for_sale', 'created_at', 'updated_at'])
                ->with(['media', 'category:id,name', 'brand:id,name', 'unit:id,short_name', 'variants:item_id,name,cost,price', 'stock:item_id,quantity'])
                ->withCount(['media'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
