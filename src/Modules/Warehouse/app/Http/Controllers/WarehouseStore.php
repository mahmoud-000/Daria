<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\Item;
use Modules\Stock\Models\Stock;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Http\Requests\StoreWarehouseRequest;

class WarehouseStore extends Controller
{
    public function __invoke(StoreWarehouseRequest $request)
    {
        try {
            $warehouse = DB::transaction(function () use ($request) {
                $warehouse = Warehouse::create($request->validated());

                $items = Item::with('variants:id,item_id')->where('deleted_at', '=', null)
                    ->get(['id', 'type'])
                    ->toArray();
                if (count($items)) {
                    $stock = [];
                    foreach ($items as $item) {
                        if ($item['type'] === ItemTypesEnum::VARIABLE->value) {
                            $variants = $item['variants'];
                            if (count($variants)) {
                                foreach ($variants as $variant) {
                                    $stock[] = [
                                        'item_id' => $item['id'],
                                        'warehouse_id' => $warehouse->id,
                                        'variant_id' => $variant['id'],
                                    ];
                                }
                            }
                        } else {
                            $stock[] = [
                                'item_id' => $item['id'],
                                'warehouse_id' => $warehouse->id,
                                'variant_id' => null,
                            ];
                        }
                    }
                    Stock::insert($stock);
                }
                return $warehouse;
            });
            return $this->success(__('status.created', ['name' => $warehouse['name'], 'module' => __('modules.warehouse')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
