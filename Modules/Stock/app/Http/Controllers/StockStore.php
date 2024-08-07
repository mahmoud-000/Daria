<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Stock\Models\Stock;
use Modules\Warehouse\Models\Warehouse;

class StockStore extends Controller
{
    public function __invoke($item, $newVariants)
    {
        try {
            $warehouses = Warehouse::where('deleted_at', null)
                ->pluck('id')
                ->toArray();

            if (count($warehouses)) {
                $stock = [];
                foreach ($warehouses as $warehouse) {
                    if (count($newVariants)) {
                        foreach ($newVariants as $variant) {
                            $stock[] = [
                                'item_id' => $item->id,
                                'warehouse_id' => $warehouse,
                                'variant_id' => $variant->id,
                                'sku' => $variant->sku
                            ];
                        }
                    } else {
                        $stock[] = [
                            'item_id' => $item->id,
                            'warehouse_id' => $warehouse,
                            'sku' => $item->sku,
                            'variant_id' => null
                        ];
                    }
                }
                return Stock::insert($stock);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createStocky($stock, $newVariants)
    {
        $warehouses = Warehouse::where('deleted_at', null)
            ->pluck('id')
            ->toArray();
        if ($warehouses) {
            $stock = [];
            foreach ($warehouses as $warehouse) {
                foreach ($newVariants as $variant) {
                    $stock[] = [
                        'stock_id' => $stock->id,
                        'warehouse_id' => $warehouse,
                        'stock_variant_id' => $variant->id,
                        // 'created_at' => now()
                    ];
                }
            }
            // Stock::insert($stock);
        }
    }
}
