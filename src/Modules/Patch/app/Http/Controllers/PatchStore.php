<?php

namespace Modules\Patch\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Patch\Models\Patch;
use Modules\Warehouse\Models\Warehouse;

class PatchStore extends Controller
{
    public function __invoke($item, $newVariants)
    {
        try {
            $warehouses = Warehouse::where('deleted_at', null)
                ->pluck('id')
                ->toArray();

            if (count($warehouses)) {
                $patch = [];
                foreach ($warehouses as $warehouse) {
                    if (count($newVariants)) {
                        foreach ($newVariants as $variant) {
                            $patch[] = [
                                'item_id' => $item->id,
                                'warehouse_id' => $warehouse,
                                'variant_id' => $variant->id,
                                // 'created_at' => now()
                            ];
                        }
                    } else {
                        $patch[] = [
                            'item_id' => $item->id,
                            'warehouse_id' => $warehouse,
                            'variant_id' => null,
                            // 'created_at' => now()
                        ];
                    }
                }
                return Patch::insert($patch);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createPatchy($patch, $newVariants)
    {
        $warehouses = Warehouse::where('deleted_at', null)
            ->pluck('id')
            ->toArray();
        if ($warehouses) {
            $patch = [];
            foreach ($warehouses as $warehouse) {
                foreach ($newVariants as $variant) {
                    $patch[] = [
                        'patch_id' => $patch->id,
                        'warehouse_id' => $warehouse,
                        'patch_variant_id' => $variant->id,
                        // 'created_at' => now()
                    ];
                }
            }
            // Patch::insert($patch);
        }
    }
}
