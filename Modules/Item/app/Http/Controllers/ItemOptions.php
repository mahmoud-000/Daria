<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Item\Transformers\ItemResource;
use Modules\Item\Models\Item;

class ItemOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return ItemResource::collection(
            Item::query()
                ->with('media')
                ->where('is_active', true)
                ->when($req->type, fn ($query) => $query->where('type', $req->type))
                ->when(!empty($req->search), fn ($query) => $query
                    ->where('name', 'LIKE', '%' . $req->search . '%')
                    ->orWhere('code', $req->search)
                )
                ->paginate(10)
            );
    }
}
