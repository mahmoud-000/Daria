<?php

namespace Modules\Item\Http\Controllers;

use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\Item;
use Modules\Item\Http\Requests\UpdateItemRequest;
use Modules\Stock\Http\Controllers\StockDestroy;
use Modules\Stock\Http\Controllers\StockStore;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Variant\Models\Variant;

class ItemUpdate extends Controller
{
    public function __invoke(UpdateItemRequest $request, Item $item)
    {
        try {
            $request = $request->validated();
            $item = DB::transaction(function () use ($item, $request) {
                $item->update(Arr::except($request, ['item_images', 'variants']));

                if (isset($request['item_images']) && !is_null($request['item_images']) && !array_key_exists('fake', $request['item_images'])) {
                    (new FilesAssign)($request['item_images'], $item, 'items', 'item_images', true);
                }

                $variants = isset($request['variants']) ? $request['variants'] : [];
                // Check if item has variants
                if ($item->type === ItemTypesEnum::VARIABLE) {
                    $this->variantsUpdateAndDestroy($item, $variants);
                }

                return $item;
            });
            return $this->success(__('status.updated', ['name' => $item['name'], 'module' => __('modules.item')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function variantsUpdateAndDestroy($item, $variants)
    {
        $existingVariantsIds = [];
        $existingVariants = [];
        $newVariantsArray = [];

        foreach ($variants as $variant) {
            // Check if the variant has id or not
            // If not, create a new variant
            // If has id, update the existing variant
            if (isset($variant['id'])) {
                $variant['item_id'] = $item->id;
                $existingVariants[] = $variant;
                $existingVariantsIds[] = $variant['id'];
            } else {
                $newVariantsArray[] = $variant;
            }
        }

        // If has variants to update
        if (count($existingVariantsIds)) {
            // Get the ids of the not existing variants
            $variantsDeletedIds = Variant::whereItemId($item->id)
                ->whereNotIn('id', $existingVariantsIds)
                ->pluck('id')
                ->toArray();
            // Update the existing variants
            Variant::upsert($existingVariants, ['id']);
            // Delete the not existing variants
            Variant::whereIn('id', $variantsDeletedIds)
                ->delete();
            // Delete the stock for the not existing variants
            (new StockDestroy)($item, $variantsDeletedIds);
        }
        // Create new variants if not empty
        $newVariantsCreated = [];
        if (count($newVariantsArray)) {
            $newVariantsCreated = $item->variants()->createMany($newVariantsArray);
        }
        // After Create new variants
        // Also create stock for new variants
        if (count($newVariantsCreated)) {
            (new StockStore)($item, $newVariantsCreated);
        }
    }
}
