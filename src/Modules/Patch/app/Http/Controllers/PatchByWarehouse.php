<?php

namespace Modules\Patch\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Enums\InvoiceTypesEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Modules\Patch\Transformers\PatchResource;
use Modules\Patch\Models\Patch;

class PatchByWarehouse extends Controller
{
    public function __invoke(Request $req)
    {
        return PatchResource::collection(
            Patch::query()
                ->with(['item', 'item.purchaseUnit', 'item.saleUnit', 'item.media', 'variant'])
                ->where('warehouse_id', $req->warehouse)
                ->whereNull('production_date')
                ->whereNull('expired_date')
                ->where(
                    fn ($query) => $query
                        ->whereHas(
                            'item',
                            fn ($query) => $query
                                ->where('is_active', ActiveEnum::ACTIVED)
                                ->where(
                                    fn ($query) => $query
                                        ->when(
                                            !empty($req->not_include) && !!count($req->not_include),
                                            fn ($query) => $query
                                                ->whereNotIn('type', $req->not_include)
                                        )
                                        ->when(
                                            !empty($req->invoice_type) && $req->invoice_type === InvoiceTypesEnum::PURCHASE->value,
                                            fn ($query) => $query
                                                ->where("is_available_for_{$req->invoice_type}", ActiveEnum::ACTIVED),
                                            fn ($query) => $query
                                                ->where('for_sale', ActiveEnum::ACTIVED)
                                        )
                                        ->when(
                                            !empty($req->search),
                                            fn ($query) => $query
                                                ->where('type', 1)
                                                ->whereCode($req->search)
                                                ->orWhere('name', 'like', '%' . $req->search . '%')
                                        )
                                )
                        )
                        ->when(
                            !empty($req->search),
                            fn ($query) => $query
                                ->orWhereHas(
                                    'variant',
                                    fn ($query) => $query
                                        ->whereCode($req->search)
                                        ->orWhere('name', 'like', '%' . $req->search . '%')
                                )
                        )
                )
                ->paginate(10)
        );
    }
}
