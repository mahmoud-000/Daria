<?php

namespace Modules\Stock\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Enums\InvoiceTypesEnum;
use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Modules\Item\Models\Item;
use Modules\Stock\Transformers\StockResource;
use Modules\Stock\Models\Stock;

class StockByWarehouse extends Controller
{
    public function __invoke(Request $req)
    {
        // return Stock::query()
        //     ->with([
        //         'item' => fn ($query) => $query->select('id', 'name', 'code', 'is_available_for_purchase', 'is_available_for_sale'),
        //         'variant' => fn ($query) => $query->select('id', 'name', 'code'),
        //         'item.media',
        //     ])
        //     ->where('warehouse_id', $req->warehouse)
        //     ->where(
        //         fn ($query) => $query
        //             ->whereHas(
        //                 'item',
        //                 fn ($query) => $query
        //                     ->select('id', 'name', 'code', 'type', 'product_type')
        //                     ->where('is_active', ActiveEnum::ACTIVED)
        //                     ->where('type', ItemTypesEnum::STANDARD)
        //                     ->where(
        //                         fn (Builder $query) => $query
        //                             ->when(
        //                                 !empty($req->not_include) && !!count($req->not_include),
        //                                 fn (Builder $query) => $query
        //                                     ->whereNotIn('type', $req->not_include)
        //                             )
        //                             ->when(
        //                                 !empty($req->invoice_type) && $req->invoice_type === InvoiceTypesEnum::PURCHASE->value,
        //                                 fn (Builder $query) => $query
        //                                     ->where("is_available_for_{$req->invoice_type}", ActiveEnum::ACTIVED),
        //                                 fn (Builder $query) => $query
        //                                     ->where('is_available_for_sale', ActiveEnum::ACTIVED)
        //                             )
        //                             ->when(
        //                                 !empty($req->search),
        //                                 fn (Builder $query) => $query
        //                                     ->where('code', $req->search)
        //                                     ->orWhere('name', 'like', '%' . $req->search . '%')
        //                             )
        //                     )
        //             )
        //             ->when(
        //                 !empty($req->search),
        //                 fn ($query) => $query
        //                     ->orWhereHas(
        //                         'variant',
        //                         fn ($query) => $query
        //                             ->whereCode($req->search)
        //                             ->orWhere('name', 'like', '%' . $req->search . '%')
        //                     )
        //             )
        //     )
        //     ->get();
        // ->ddRawSql();


        StockResource::collection(
            Stock::query()
                ->with([
                    'item' => fn ($query) => $query->select('id', 'name'), 'item.media', 'variant'
                ])
                ->where('warehouse_id', $req->warehouse)
                ->where(
                    fn (Builder $query) => $query
                        ->whereHas(
                            'item',
                            fn (Builder $query) => $query
                                // ->select('id', 'name', 'code', 'type', 'product_type')
                                ->where('is_active', ActiveEnum::ACTIVED)
                                ->where('type', ItemTypesEnum::STANDARD)
                                ->where(
                                    fn (Builder $query) => $query
                                        ->when(
                                            !empty($req->not_include) && !!count($req->not_include),
                                            fn (Builder $query) => $query
                                                ->whereNotIn('type', $req->not_include)
                                        )
                                        ->when(
                                            !empty($req->invoice_type) && $req->invoice_type === InvoiceTypesEnum::PURCHASE->value,
                                            fn (Builder $query) => $query
                                                ->where("is_available_for_{$req->invoice_type}", ActiveEnum::ACTIVED),
                                            fn (Builder $query) => $query
                                                ->where('is_available_for_sale', ActiveEnum::ACTIVED)
                                        )
                                        ->when(
                                            !empty($req->search),
                                            fn (Builder $query) => $query
                                                ->where('code', $req->search)
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
                ->get()
        );


        return StockResource::collection(
            Stock::query()
                ->with(['patches', 'item', 'item.purchaseUnit', 'item.saleUnit', 'item.media', 'variant'])
                ->where('warehouse_id', $req->warehouse)
                ->where(
                    fn ($query) => $query
                        ->whereHas(
                            'item',
                            fn ($query) => $query
                                ->where('is_active', ActiveEnum::ACTIVED)
                                ->where('type', ItemTypesEnum::STANDARD)
                                ->where(
                                    fn (Builder $query) => $query
                                        ->when(
                                            !empty($req->not_include) && !!count($req->not_include),
                                            fn (Builder $query) => $query
                                                ->whereNotIn('type', $req->not_include)
                                        )
                                        ->when(
                                            !empty($req->invoice_type) && $req->invoice_type === InvoiceTypesEnum::PURCHASE->value,
                                            fn (Builder $query) => $query
                                                ->where("is_available_for_{$req->invoice_type}", ActiveEnum::ACTIVED),
                                            fn (Builder $query) => $query
                                                ->where('is_available_for_sale', ActiveEnum::ACTIVED)
                                        )
                                        ->when(
                                            !empty($req->search),
                                            fn (Builder $query) => $query
                                                ->where('code', $req->search)
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
